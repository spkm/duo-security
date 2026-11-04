<?php

use Illuminate\Support\Facades\Cache;
use Saloon\Http\Faking\MockResponse;
use spkm\DuoSecurity\Admin\AdminConnector;
use spkm\DuoSecurity\Admin\Data\Administrators\ActivationLink;
use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Admin\Data\Integrations\ClientSecret;
use spkm\DuoSecurity\Admin\Data\Integrations\Integration;
use spkm\DuoSecurity\Admin\Data\Integrations\SecretKey;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetIntegrationSecretKeyRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\CreateTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Users\GetUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\GetUserVerificationPushResponseRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUsersRequest;
use spkm\DuoSecurity\Auth\Data\AuthResult;
use spkm\DuoSecurity\Auth\Data\AuthStatus;
use spkm\DuoSecurity\Auth\Data\Enrollment;
use spkm\DuoSecurity\Auth\Requests\AuthRequest;
use spkm\DuoSecurity\Auth\Requests\AuthStatusRequest;
use spkm\DuoSecurity\Auth\Requests\CheckRequest;
use spkm\DuoSecurity\Auth\Requests\PreauthRequest;
use spkm\DuoSecurity\DuoApiException;
use spkm\DuoSecurity\Exceptions\InvalidRequestException;
use spkm\DuoSecurity\Facades\DuoSecurity;
use spkm\DuoSecurity\Requests\AdhocRequest;
use spkm\DuoSecurity\Signing\Credentials;
use spkm\DuoSecurity\Support\Path;
use spkm\DuoSecurity\Tests\Fixtures\ExampleGetRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

/*
 * What an independent security review of the package found, each pinned here so it stays fixed.
 */

function vardump(mixed $value): string
{
    return (string) (new CliDumper)->dump((new VarCloner)->cloneVar($value), true);
}

// --- The response cache -----------------------------------------------------------------------------------------

it('serves a cached answer only to a connector with the same secret key, not just the same integration key', function () {
    config()->set('duo-security.cache.enabled', true);
    FakeDuo::respondWith([GetUserRequest::class => FakeDuo::ok(['user_id' => 'DU1'])]);

    $first = FakeDuo::admin()->send(new GetUserRequest('DU1'));
    $sameKeys = FakeDuo::admin()->send(new GetUserRequest('DU1'));
    $wrongSecret = (new AdminConnector(new Credentials(FakeDuo::INTEGRATION_KEY, 'not-the-secret', FakeDuo::HOST)))->send(new GetUserRequest('DU1'));

    expect($first->isCached())->toBeFalse()
        ->and($sameKeys->isCached())->toBeTrue()
        ->and($wrongSecret->isCached())->toBeFalse();
});

it('never caches an answer that carries a secret, a status being polled, or the Auth API', function (string $class, array $args) {
    config()->set('duo-security.cache.enabled', true);
    $mockClient = FakeDuo::respondWith([$class => FakeDuo::ok(['skey' => 'x', 'result' => 'waiting', 'time' => 1])]);
    $connector = str_starts_with($class, 'spkm\\DuoSecurity\\Auth\\') ? FakeDuo::auth() : FakeDuo::admin();

    $connector->send(new $class(...$args));
    $again = $connector->send(new $class(...$args));

    expect($again->isCached())->toBeFalse()
        ->and(FakeDuo::responsesTo($mockClient, $class))->toHaveCount(2);
})->with([
    [GetIntegrationSecretKeyRequest::class, ['DI1']],
    [GetUserVerificationPushResponseRequest::class, ['DU1', 'P1']],
    [AuthStatusRequest::class, ['tx1']],
    [CheckRequest::class, []],
]);

it('caches a record read, as the config says, and keeps it out of PHP serialisation', function () {
    config()->set('duo-security.cache.enabled', true);
    FakeDuo::respondWith([GetUserRequest::class => FakeDuo::ok(['user_id' => 'DU1']), ListUsersRequest::class => FakeDuo::page([['user_id' => 'DU1']])]);
    $connector = FakeDuo::admin();

    $connector->send(new GetUserRequest('DU1'));
    $connector->send(new ListUsersRequest);
    $entries = (fn () => $this->storage)->call(Cache::store('array')->getStore());

    expect($connector->send(new GetUserRequest('DU1'))->isCached())->toBeTrue()
        ->and($connector->send(new ListUsersRequest)->isCached())->toBeTrue()
        ->and($entries)->not->toBeEmpty();

    foreach ($entries as $entry) {
        expect($entry['value'])->toBeString()->not->toStartWith('O:')->and(json_validate($entry['value']))->toBeTrue();
    }
});

it('treats a FAIL envelope with a 200 as a failure everywhere: throw(), failed(), retries and the cache agree with dto()', function () {
    config()->set('duo-security.cache.enabled', true);
    $mockClient = FakeDuo::respondWith([GetUserRequest::class => FakeDuo::fail(40002, 'Invalid request parameters', 'user_id', 200)]);
    $connector = FakeDuo::admin();

    $response = $connector->send(new GetUserRequest('DU1'));
    $again = $connector->send(new GetUserRequest('DU1'));

    expect($response->failed())->toBeTrue()
        ->and(fn () => $response->throw())->toThrow(InvalidRequestException::class)
        ->and(fn () => $response->dtoOrFail())->toThrow(LogicException::class)
        ->and($again->isCached())->toBeFalse()
        ->and(FakeDuo::responsesTo($mockClient, GetUserRequest::class))->toHaveCount(2);
});

// --- Secrets in dumps and serialisation -------------------------------------------------------------------------

it('keeps the secret key out of every dump: VarDumper, var_export, print_r, var_dump and json', function () {
    $credentials = new Credentials(FakeDuo::INTEGRATION_KEY, 'THE-SECRET-VALUE', FakeDuo::HOST);
    $connector = new AdminConnector($credentials);
    FakeDuo::respondWith([ExampleGetRequest::class => MockResponse::make(['stat' => 'FAIL', 'code' => 40002, 'message' => 'x'], 400)]);
    $exception = DuoApiException::fromResponse($connector->send(new ExampleGetRequest('DU1')));

    foreach ([$credentials, $connector, $exception] as $subject) {
        expect(vardump($subject))->not->toContain('THE-SECRET-VALUE')
            ->and(print_r($subject, true))->not->toContain('THE-SECRET-VALUE')
            ->and(json_encode((array) $subject))->not->toContain('THE-SECRET-VALUE');
    }

    // var_export refuses the connector and the exception (they hold circular references), so only the credentials.
    expect(var_export($credentials, true))->not->toContain('THE-SECRET-VALUE');

    expect(method_exists($credentials, 'secretKey'))->toBeFalse()
        ->and($credentials->signature("canonical\nstring"))->toBe(hash_hmac('sha512', "canonical\nstring", 'THE-SECRET-VALUE'))
        ->and($credentials->fingerprint())->not->toBe((new Credentials(FakeDuo::INTEGRATION_KEY, 'other', FakeDuo::HOST))->fingerprint())
        ->and($credentials->fingerprint())->toBe((new Credentials(FakeDuo::INTEGRATION_KEY, 'THE-SECRET-VALUE', FakeDuo::HOST))->fingerprint())
        ->and($credentials->fingerprint())->not->toContain('THE-SECRET-VALUE');
});

it('leaves a model\'s secrets out of toArray(), json and dumps unless asked for them', function (string $class, string $field) {
    $model = $class::from([$field => 'THE-SECRET-VALUE', 'name' => 'x']);

    expect($model->{$field})->toBe('THE-SECRET-VALUE')
        ->and($model->toArray())->not->toHaveKey($field)
        ->and($model->toArray(withHidden: true))->toHaveKey($field, 'THE-SECRET-VALUE')
        ->and(json_encode($model))->not->toContain('THE-SECRET-VALUE')
        ->and(vardump($model))->not->toContain('THE-SECRET-VALUE')
        ->and(print_r($model, true))->not->toContain('THE-SECRET-VALUE')
        ->and($class::hidden())->toContain($field);
})->with([
    [Integration::class, 'secret_key'],
    [SecretKey::class, 'skey'],
    [ClientSecret::class, 'client_secret'],
    [Enrollment::class, 'activation_code'],
    [Enrollment::class, 'activation_url'],
    [AuthResult::class, 'trusted_device_token'],
    [AuthStatus::class, 'trusted_device_token'],
    [Admin::class, 'activation_url'],
    [ActivationLink::class, 'code'],
]);

it('redacts the parameters of a request that carries a secret when it is dumped', function () {
    $token = new CreateTokenRequest('h6', '0123', ['secret' => 'THE-SECRET-VALUE']);
    $auth = new AuthRequest(['username' => 'jsmith', 'factor' => 'passcode', 'passcode' => 'THE-SECRET-VALUE']);
    $preauth = new PreauthRequest(['username' => 'jsmith', 'trusted_device_token' => 'THE-SECRET-VALUE']);

    foreach ([$token, $auth, $preauth] as $request) {
        expect(vardump($request))->not->toContain('THE-SECRET-VALUE')
            ->and(print_r($request, true))->not->toContain('THE-SECRET-VALUE');
    }
});

// --- Paths and hostnames ----------------------------------------------------------------------------------------

it('refuses an ad-hoc path that would change the endpoint: a fragment, a stray ?, an encoded dot segment', function (string $path) {
    expect(fn () => Path::api($path))->toThrow(InvalidArgumentException::class);
})->with([
    '/admin/v1/users/DUVICTIM#/phones/P1',
    '/admin/v1/users/DU1?username=x?other=y',
    '/admin/v1/%2e%2e/etc',
    '/admin/v1/.%2E/etc',
    '/admin/v1/users/DU1/../DU2',
]);

it('fills an ad-hoc path\'s placeholders with encoded segments, so an id cannot escape its place', function () {
    $mockClient = FakeDuo::respondWith([AdhocRequest::class => FakeDuo::ok([])]);
    $connector = FakeDuo::admin();

    $connector->get('/admin/v1/users/{id}/phones/{phone}', ['limit' => 1], ['id' => 'DUVICTIM#', 'phone' => 'P/1']);
    $connector->delete('/admin/v1/users/{id}', segments: ['id' => 'DU1?x']);

    expect(FakeDuo::urisReceived($mockClient, AdhocRequest::class))->toBe([
        FakeDuo::BASE_URL.'/admin/v1/users/DUVICTIM%23/phones/P%2F1?limit=1',
        FakeDuo::BASE_URL.'/admin/v1/users/DU1%3Fx',
    ])
        ->and(fn () => $connector->get('/admin/v1/users/{id}', segments: []))->toThrow(InvalidArgumentException::class, 'id')
        ->and(fn () => $connector->get('/admin/v1/users/{id}', segments: ['id' => '..']))->toThrow(InvalidArgumentException::class);
});

it('refuses an API hostname that is not a bare hostname', function (string $hostname) {
    expect(fn () => new Credentials('ikey', 'skey', $hostname))->toThrow(InvalidArgumentException::class);
})->with([
    'api-x.duosecurity.com@evil.example',
    'api-x.duosecurity.com:8443',
    'api-x.duosecurity.com?x=1',
    'api-x.duosecurity.com#frag',
    'api-x.duosecurity.com\\evil',
    'api x.duosecurity.com',
    'localhost',
    '127.0.0.1',
    '-api.duosecurity.com',
]);

it('still accepts a hostname written with a scheme or a trailing slash', function (string $hostname) {
    expect((new Credentials('ikey', 'skey', $hostname))->apiHostname)->toBe('api-xxxxxxxx.duosecurity.com');
})->with(['api-xxxxxxxx.duosecurity.com', 'https://api-xxxxxxxx.duosecurity.com/', 'API-XXXXXXXX.DUOSECURITY.COM', 'https://api-xxxxxxxx.duosecurity.com/admin/v1/']);

// --- The facade and the two APIs --------------------------------------------------------------------------------

it('refuses to send an Auth API request through the Admin API connector, which would sign it with the wrong keys', function () {
    config()->set('duo-security.admin', ['integration_key' => FakeDuo::INTEGRATION_KEY, 'secret_key' => FakeDuo::SECRET_KEY, 'api_hostname' => FakeDuo::HOST]);
    FakeDuo::respondWith([PreauthRequest::class => FakeDuo::ok(['result' => 'allow'])]);

    expect(fn () => DuoSecurity::send(new PreauthRequest(['username' => 'x'])))->toThrow(LogicException::class, 'authConnector()')
        ->and(fn () => DuoSecurity::admin()->send(new PreauthRequest(['username' => 'x'])))->toThrow(LogicException::class, 'Auth API');
});

// --- Exception text ---------------------------------------------------------------------------------------------

it('cleans what Duo said of format characters and invalid UTF-8 before it goes in a message', function () {
    $formatted = FakeDuo::send(new ExampleGetRequest('DU1'), MockResponse::make(['stat' => 'FAIL', 'code' => 40002, 'message' => "Bad \u{202E}evil\u{200B} param", 'message_detail' => 'user_id'], 400));
    $invalid = FakeDuo::send(new ExampleGetRequest('DU1'), MockResponse::make("Gateway says \xC3\x28 no", 502, ['Content-Type' => 'text/plain']));

    expect(DuoApiException::fromResponse($formatted)->getMessage())->toBe('Duo answered 400 to GET /admin/v1/examples/DU1: Bad evil param (user_id)')
        ->and(DuoApiException::fromResponse($invalid)->getMessage())->toBe('Duo answered 502 to GET /admin/v1/examples/DU1 with a body that is not JSON: Gateway says ?( no')
        ->and(mb_check_encoding(DuoApiException::fromResponse($invalid)->getMessage(), 'UTF-8'))->toBeTrue();
});
