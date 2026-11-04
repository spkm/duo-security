<?php

use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Support\Carbon;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Http\Faking\MockResponse;
use spkm\DuoSecurity\Signing\DuoAuthenticator;
use spkm\DuoSecurity\Signing\DuoSigner;
use spkm\DuoSecurity\Tests\Fixtures\ExampleDeleteRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleGetRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleHeadersRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleListRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleUnsignedRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleWriteRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

it('dates every request in UTC as RFC 2822 writes it, and signs it with the integration key', function () {
    Carbon::setTestNow('2026-11-04 09:00:00');
    $mockClient = FakeDuo::respondWith([ExampleGetRequest::class => FakeDuo::ok(['user_id' => 'DU1'])]);

    $response = FakeDuo::admin()->send(new ExampleGetRequest('DU1'));

    expect(FakeDuo::headersReceived($mockClient, ExampleGetRequest::class, 'Date'))->toBe(['Wed, 04 Nov 2026 09:00:00 +0000'])
        ->and(FakeDuo::authorizationsReceived($mockClient, ExampleGetRequest::class))->toBe([FakeDuo::signatureFor($response)])
        ->and(base64_decode(substr($response->getPsrRequest()->getHeaderLine('Authorization'), 6)))->toStartWith(FakeDuo::INTEGRATION_KEY.':')
        ->and(FakeDuo::headersReceived($mockClient, ExampleGetRequest::class, 'Accept'))->toBe(['application/json']);
});

it('signs the query string exactly as it is sent: sorted, RFC 3986 encoded, with no + for a space', function () {
    $mockClient = FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::page([])]);

    $response = FakeDuo::admin()->send(new ExampleListRequest(['username' => 'root', 'realname' => 'First Last', 'email' => 'a+b@example.com', 'notes' => "caf\u{e9} ~/", 'preserve_existing' => true, 'count' => 2, 'absent' => null]));

    expect(FakeDuo::queriesReceived($mockClient, ExampleListRequest::class))->toBe(['count=2&email=a%2Bb%40example.com&notes=caf%C3%A9%20~%2F&preserve_existing=true&realname=First%20Last&username=root'])
        ->and($response->getPsrRequest()->getHeaderLine('Authorization'))->toBe(FakeDuo::signatureFor($response))
        ->and(explode("\n", DuoSigner::canonical('d', 'GET', FakeDuo::HOST, '/admin/v1/examples', $response->getPsrRequest()->getUri()->getQuery(), ''))[4])->toContain('realname=First%20Last');
});

it('signs the JSON body exactly as it is sent, with the keys sorted, and {} when there are no parameters', function () {
    $mockClient = FakeDuo::respondWith([ExampleWriteRequest::class => FakeDuo::ok(['user_id' => 'DU1'])]);
    $connector = FakeDuo::admin();

    $with = $connector->send(new ExampleWriteRequest(['username' => 'root', 'realname' => "First L\u{e9}st", 'notes' => 'a/b', 'enable_auto_prompt' => true]));
    $without = $connector->send(new ExampleWriteRequest);

    expect(FakeDuo::bodiesReceived($mockClient, ExampleWriteRequest::class))->toBe([json_encode(['enable_auto_prompt' => true, 'notes' => 'a/b', 'realname' => "First L\u{e9}st", 'username' => 'root']), '{}'])
        ->and(FakeDuo::headersReceived($mockClient, ExampleWriteRequest::class, 'Content-Type'))->toBe(['application/json', 'application/json'])
        ->and(FakeDuo::queriesReceived($mockClient, ExampleWriteRequest::class))->toBe(['', ''])
        ->and($with->getPsrRequest()->getHeaderLine('Authorization'))->toBe(FakeDuo::signatureFor($with))
        ->and($without->getPsrRequest()->getHeaderLine('Authorization'))->toBe(FakeDuo::signatureFor($without))
        ->and($with->getPsrRequest()->getHeaderLine('Authorization'))->not->toBe($without->getPsrRequest()->getHeaderLine('Authorization'));
});

it('sends a DELETE\'s parameters in the query string, with an empty body', function () {
    $mockClient = FakeDuo::respondWith([ExampleDeleteRequest::class => FakeDuo::ok()]);

    $response = FakeDuo::admin()->send(new ExampleDeleteRequest('DU1', ['registered_device_key_list' => ['C1', 'C2']]));

    expect(FakeDuo::urisReceived($mockClient, ExampleDeleteRequest::class))->toBe([FakeDuo::BASE_URL.'/admin/v1/examples/DU1?registered_device_key_list=%5B%22C1%22%2C%22C2%22%5D'])
        ->and(FakeDuo::bodiesReceived($mockClient, ExampleDeleteRequest::class))->toBe([''])
        ->and($response->getPsrRequest()->getHeaderLine('Authorization'))->toBe(FakeDuo::signatureFor($response));
});

it('signs the X-Duo-* headers a request carries, so the signature changes with them', function () {
    $mockClient = FakeDuo::respondWith([ExampleHeadersRequest::class => FakeDuo::ok([]), ExampleGetRequest::class => FakeDuo::ok([])]);
    Carbon::setTestNow('2026-11-04 09:00:00');

    $withHeaders = FakeDuo::admin()->send(new ExampleHeadersRequest);
    $plain = FakeDuo::admin()->send(new ExampleGetRequest('DU1'));
    $psr = $withHeaders->getPsrRequest();
    $recomputed = DuoSigner::sign(FakeDuo::credentials(), $psr->getHeaderLine('Date'), 'GET', '/admin/v1/examples', '', '', ['X-Duo-Request-Id' => 'abc-123', 'x-duo-other' => 'two']);

    expect(FakeDuo::headersReceived($mockClient, ExampleHeadersRequest::class, 'X-Duo-Request-Id'))->toBe(['abc-123'])
        ->and($psr->getHeaderLine('Authorization'))->toBe($recomputed)
        ->and($psr->getHeaderLine('Authorization'))->toBe(FakeDuo::signatureFor($withHeaders))
        ->and($plain->getPsrRequest()->getHeaderLine('Authorization'))->not->toBe($psr->getHeaderLine('Authorization'));
});

it('sends the one unsigned call, the Auth API\'s ping, without a Date or an Authorization header', function () {
    $mockClient = FakeDuo::respondWith([ExampleUnsignedRequest::class => FakeDuo::ok(['time' => 1357020061])]);

    $response = FakeDuo::auth()->send(new ExampleUnsignedRequest);

    expect(FakeDuo::authorizationsReceived($mockClient, ExampleUnsignedRequest::class))->toBe([''])
        ->and(FakeDuo::headersReceived($mockClient, ExampleUnsignedRequest::class, 'Date'))->toBe([''])
        ->and($response->dto())->toBe(['time' => 1357020061]);
});

it('signs each attempt afresh when a request is retried, with the time it is sent', function () {
    config()->set('duo-security.retries', ['times' => 3, 'interval_ms' => 1]);
    $attempt = 0;
    $mockClient = FakeDuo::respondWith([ExampleGetRequest::class => function () use (&$attempt) {
        // Duo's answer to this attempt; the clock moves on for the next one.
        Carbon::setTestNow('2026-11-04 09:00:0'.(++$attempt));

        return $attempt < 3 ? MockResponse::make('', 503) : FakeDuo::ok(['user_id' => 'DU1']);
    }]);
    Carbon::setTestNow('2026-11-04 09:00:00');

    $response = FakeDuo::admin()->send(new ExampleGetRequest('DU1'));
    $responses = FakeDuo::responsesTo($mockClient, ExampleGetRequest::class);
    $authorizations = FakeDuo::authorizationsReceived($mockClient, ExampleGetRequest::class);

    expect($response->status())->toBe(200)
        ->and($responses)->toHaveCount(3)
        ->and(FakeDuo::headersReceived($mockClient, ExampleGetRequest::class, 'Date'))->toBe(['Wed, 04 Nov 2026 09:00:00 +0000', 'Wed, 04 Nov 2026 09:00:01 +0000', 'Wed, 04 Nov 2026 09:00:02 +0000'])
        ->and(count(array_unique($authorizations)))->toBe(3)
        ->and($authorizations[2])->toBe(FakeDuo::signatureFor($responses[2]));
});

it('signs a request built by hand from the bytes it holds, dating it when it has no date', function () {
    Carbon::setTestNow('2026-11-04 09:00:00');
    $authenticator = new DuoAuthenticator(FakeDuo::credentials());
    $factory = new HttpFactory;
    $request = $factory->createRequest('POST', 'https://'.FakeDuo::HOST.'/admin/v1/users?username=root')->withBody($factory->createStream('{"a":"b"}'));

    $signed = $authenticator->sign($request);
    $expected = DuoSigner::sign(FakeDuo::credentials(), 'Wed, 04 Nov 2026 09:00:00 +0000', 'POST', '/admin/v1/users', 'username=root', '{"a":"b"}');

    expect($signed->getHeaderLine('Date'))->toBe('Wed, 04 Nov 2026 09:00:00 +0000')
        ->and($signed->getHeaderLine('Authorization'))->toBe($expected)
        ->and((string) $signed->getBody())->toBe('{"a":"b"}')
        ->and($authenticator->credentials())->toBe($authenticator->credentials())
        ->and($authenticator->sign($request->withHeader('Date', 'Tue, 21 Aug 2012 17:29:18 -0000'))->getHeaderLine('Authorization'))->not->toBe($expected);
});

it('retries a write that asked to be sent again after a dropped connection, and no other', function () {
    FakeDuo::respondWith([ExampleWriteRequest::class => FakeDuo::ok([])]);
    $connector = FakeDuo::admin();
    $fatal = new FatalRequestException(new RuntimeException('down'), $connector->createPendingRequest(new ExampleWriteRequest));

    expect($connector->handleRetry($fatal, new ExampleWriteRequest))->toBeFalse()
        ->and($connector->handleRetry($fatal, (new ExampleWriteRequest)->retryingDroppedConnections()))->toBeTrue()
        ->and($connector->handleRetry($fatal, (new ExampleWriteRequest)->retryingDroppedConnections()->retryingDroppedConnections(false)))->toBeFalse()
        ->and($connector->handleRetry($fatal, new ExampleGetRequest('DU1')))->toBeTrue();
});
