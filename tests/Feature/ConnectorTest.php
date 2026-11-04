<?php

use Saloon\Config;
use Saloon\Enums\Method;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Http\Faking\MockResponse;
use spkm\DuoSecurity\Auth\AuthConnector;
use spkm\DuoSecurity\DuoLogPaginator;
use spkm\DuoSecurity\DuoPaginator;
use spkm\DuoSecurity\DuoTimestampPaginator;
use spkm\DuoSecurity\Requests\AdhocBodyRequest;
use spkm\DuoSecurity\Requests\AdhocRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleGetRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleListRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleLogRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleTimedLogRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleWriteRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

it('talks to Duo at https://{api hostname}, however the hostname is written', function (string $host) {
    $connector = FakeDuo::admin($host);

    expect($connector->resolveBaseUrl())->toBe(FakeDuo::BASE_URL)
        ->and($connector->credentials()->apiHostname)->toBe(FakeDuo::HOST)
        ->and($connector->credentialsFingerprint())->toBe($connector->credentials()->fingerprint());
})->with([FakeDuo::HOST, 'https://'.FakeDuo::HOST.'/', strtoupper(FakeDuo::HOST)]);

it('contacts nobody when a connector is built', function () {
    $mockClient = FakeDuo::respondWith();

    FakeDuo::admin();
    FakeDuo::auth();

    expect($mockClient->getRecordedResponses())->toBe([]);
});

it('takes its timeouts and retries from config, and waits at least 70 seconds for the Auth API to long-poll', function () {
    config()->set('duo-security.timeouts', ['connect' => 5, 'request' => 20]);
    config()->set('duo-security.retries', ['times' => 3, 'interval_ms' => 250]);
    $admin = FakeDuo::admin();
    $auth = FakeDuo::auth();

    expect($admin->getConnectTimeout())->toBe(5.0)
        ->and($admin->getRequestTimeout())->toBe(20.0)
        ->and($auth->getRequestTimeout())->toBe(AuthConnector::LONG_POLL_TIMEOUT)
        ->and($admin->tries)->toBe(3)
        ->and($admin->retryInterval)->toBe(250)
        ->and($admin->useExponentialBackoff)->toBeTrue()
        ->and($admin->throwOnMaxTries)->toBeFalse();

    config()->set('duo-security.timeouts', ['connect' => 'x', 'request' => 0]);
    config()->set('duo-security.retries', ['times' => null]);
    $plain = FakeDuo::admin();

    expect($plain->getConnectTimeout())->toBe((float) Config::$defaultConnectionTimeout)
        ->and($plain->getRequestTimeout())->toBe((float) Config::$defaultRequestTimeout)
        ->and($plain->tries)->toBeNull();
});

it('retries a GET, not a write, on a server error, a timeout or too many requests', function (int $status) {
    config()->set('duo-security.retries', ['times' => 3, 'interval_ms' => 1]);
    $mockClient = FakeDuo::respondWith([
        ExampleGetRequest::class => MockResponse::make('', $status),
        ExampleWriteRequest::class => MockResponse::make('', $status),
    ]);
    $connector = FakeDuo::admin();

    $connector->send(new ExampleGetRequest('DU1'));
    $connector->send(new ExampleWriteRequest(['username' => 'x']));

    expect(FakeDuo::responsesTo($mockClient, ExampleGetRequest::class))->toHaveCount(3)
        ->and(FakeDuo::responsesTo($mockClient, ExampleWriteRequest::class))->toHaveCount(1);
})->with([500, 503, 408, 429]);

it('does not retry a GET that Duo refused', function () {
    config()->set('duo-security.retries', ['times' => 3, 'interval_ms' => 1]);
    $mockClient = FakeDuo::respondWith([ExampleGetRequest::class => FakeDuo::fail(40401, 'Resource not found')]);

    $response = FakeDuo::admin()->send(new ExampleGetRequest('DU1'));

    expect($response->status())->toBe(404)
        ->and(FakeDuo::responsesTo($mockClient, ExampleGetRequest::class))->toHaveCount(1);
});

it('retries a GET, not a write, when the connection fails', function () {
    FakeDuo::respondWith([ExampleGetRequest::class => FakeDuo::ok()]);
    $connector = FakeDuo::admin();
    $fatal = new FatalRequestException(new RuntimeException('down'), $connector->createPendingRequest(new ExampleGetRequest('DU1')));

    expect($connector->handleRetry($fatal, new ExampleGetRequest('DU1')))->toBeTrue()
        ->and($connector->handleRetry($fatal, new ExampleWriteRequest))->toBeFalse()
        ->and($connector->handleRetry($fatal, new AdhocRequest(Method::GET, '/admin/v1/users')))->toBeTrue()
        ->and($connector->handleRetry($fatal, new AdhocRequest(Method::DELETE, '/admin/v1/users/DU1')))->toBeFalse();
});

it('makes ad-hoc calls by method and path from the root of the API hostname', function () {
    $mockClient = FakeDuo::respondWith([FakeDuo::BASE_URL.'/*' => FakeDuo::ok(['user_id' => 'DU9'])]);
    $connector = FakeDuo::admin();

    expect($connector->get('admin/v1/users', ['username' => 'root'])->dto())->toBe(['user_id' => 'DU9'])
        ->and($connector->post('/admin/v1/users', ['username' => 'root'])->dto())->toBe(['user_id' => 'DU9'])
        ->and($connector->put('/admin/v2/policies/P1', ['policy_name' => 'x'])->dto())->toBe(['user_id' => 'DU9'])
        ->and($connector->delete('/admin/v1/users/DU9', ['reason' => 'gone'])->dto())->toBe(['user_id' => 'DU9'])
        ->and(FakeDuo::urisReceived($mockClient, AdhocRequest::class))->toBe([FakeDuo::BASE_URL.'/admin/v1/users?username=root', FakeDuo::BASE_URL.'/admin/v1/users', FakeDuo::BASE_URL.'/admin/v2/policies/P1', FakeDuo::BASE_URL.'/admin/v1/users/DU9?reason=gone'])
        ->and(FakeDuo::urisReceived($mockClient, AdhocBodyRequest::class))->toBe([FakeDuo::BASE_URL.'/admin/v1/users', FakeDuo::BASE_URL.'/admin/v2/policies/P1'])
        ->and(FakeDuo::bodiesReceived($mockClient, AdhocBodyRequest::class))->toBe(['{"username":"root"}', '{"policy_name":"x"}'])
        ->and(fn () => $connector->get('/admin/v1/../etc'))->toThrow(InvalidArgumentException::class);
});

it('keeps a query string written into an ad-hoc path, merged with the parameters given', function () {
    $mockClient = FakeDuo::respondWith([AdhocRequest::class => FakeDuo::ok([])]);

    FakeDuo::admin()->get('/admin/v1/users?keep=a+b&username=old', ['username' => 'root']);

    expect(FakeDuo::queriesReceived($mockClient, AdhocRequest::class))->toBe(['keep=a%20b&username=root']);
});

it('pages a list by offset, a v2 log by cursor and a v1 log by time', function () {
    $connector = FakeDuo::admin();

    expect($connector->paginate(new ExampleListRequest))->toBeInstanceOf(DuoPaginator::class)
        ->and($connector->paginate(new ExampleLogRequest))->toBeInstanceOf(DuoLogPaginator::class)
        ->and($connector->paginate(new ExampleTimedLogRequest))->toBeInstanceOf(DuoTimestampPaginator::class);
});

it('shows the hostname and the integration key in a dump, never the secret key, and cannot be serialised', function () {
    $connector = FakeDuo::admin();

    expect($connector->__debugInfo())->toBe(['apiHostname' => FakeDuo::HOST, 'integrationKey' => FakeDuo::INTEGRATION_KEY])
        ->and(print_r($connector, true))->not->toContain(FakeDuo::SECRET_KEY)
        ->and(fn () => serialize($connector))->toThrow(LogicException::class);
});
