<?php

use Saloon\Http\Faking\MockResponse;
use spkm\DuoSecurity\DuoApiException;
use spkm\DuoSecurity\Exceptions\InvalidRequestException;
use spkm\DuoSecurity\Exceptions\NotFoundException;
use spkm\DuoSecurity\Exceptions\RateLimitedException;
use spkm\DuoSecurity\Exceptions\ServerErrorException;
use spkm\DuoSecurity\Exceptions\UnauthorizedException;
use spkm\DuoSecurity\Tests\Fixtures\ExampleGetRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleWriteRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

it('gives the exception callers handle for each of Duo\'s codes, with the HTTP status as its code', function (int $code, string $class) {
    $response = FakeDuo::send(new ExampleGetRequest('DU1'), FakeDuo::fail($code, 'Something', 'detail'));
    $exception = DuoApiException::fromResponse($response);

    expect($exception::class)->toBe($class)
        ->and($exception)->toBeInstanceOf(DuoApiException::class)
        ->and($exception->getResponse())->toBe($response)
        ->and($exception->getCode())->toBe(intdiv($code, 100))
        ->and($exception->duoCode())->toBe($code)
        ->and($exception->duoMessage())->toBe('Something')
        ->and($exception->messageDetail())->toBe('detail')
        ->and(fn () => $response->dto())->toThrow($class)
        ->and(fn () => $response->throw())->toThrow($class);
})->with([
    [40401, NotFoundException::class],
    [40101, UnauthorizedException::class],
    [40301, UnauthorizedException::class],
    [42901, RateLimitedException::class],
    [40001, InvalidRequestException::class],
    [40002, InvalidRequestException::class],
    [40501, InvalidRequestException::class],
    [40901, InvalidRequestException::class],
    [50001, ServerErrorException::class],
    [41801, DuoApiException::class],
]);

it('falls back to the HTTP status when Duo gave no code', function (int $status, string $class) {
    $response = FakeDuo::send(new ExampleGetRequest('DU1'), MockResponse::make('<html>Bad gateway</html>', $status, ['Content-Type' => 'text/html']));
    $exception = DuoApiException::fromResponse($response);

    expect($exception::class)->toBe($class)
        ->and($exception->getCode())->toBe($status)
        ->and($exception->duoCode())->toBeNull()
        ->and($exception->getMessage())->toBe("Duo answered $status to GET /admin/v1/examples/DU1 with a body that is not JSON: Bad gateway");
})->with([
    [404, NotFoundException::class],
    [403, UnauthorizedException::class],
    [429, RateLimitedException::class],
    [400, InvalidRequestException::class],
    [408, ServerErrorException::class],
    [502, ServerErrorException::class],
]);

it('treats a 2xx without the OK envelope as an error: by Duo\'s code for a FAIL with a 200, else a server error', function () {
    $failed = FakeDuo::send(new ExampleGetRequest('DU1'), FakeDuo::fail(40002, 'Invalid request parameters', 'username', 200));
    $html = FakeDuo::send(new ExampleGetRequest('DU1'), MockResponse::make('<html><title>Maintenance</title></html>', 200, ['Content-Type' => 'text/html']));
    $empty = FakeDuo::send(new ExampleGetRequest('DU1'), MockResponse::make('', 200));
    $list = FakeDuo::send(new ExampleGetRequest('DU1'), MockResponse::make('[1,2]', 200, ['Content-Type' => 'application/json']));

    expect(fn () => $failed->dto())->toThrow(InvalidRequestException::class, 'Duo answered 200 to GET /admin/v1/examples/DU1: Invalid request parameters (username)')
        ->and(fn () => $html->dto())->toThrow(ServerErrorException::class, 'with a body that is not JSON: Maintenance')
        ->and(fn () => $empty->dto())->toThrow(ServerErrorException::class, 'with an empty body')
        ->and(fn () => $list->dto())->toThrow(ServerErrorException::class, 'without the OK envelope expected')
        ->and(DuoApiException::fromResponse($failed))->toBeInstanceOf(InvalidRequestException::class);
});

it('says what Duo said, cleaned to one line of at most 200 characters', function () {
    $response = FakeDuo::send(new ExampleWriteRequest(['username' => '']), FakeDuo::fail(40002, "Invalid request\n <b>parameters</b>.", str_repeat('u', 300)));
    $bare = FakeDuo::send(new ExampleWriteRequest, FakeDuo::fail(40004, 'Operation failed'));
    $exception = DuoApiException::fromResponse($response);

    expect($exception->getMessage())->toStartWith('Duo answered 400 to POST /admin/v1/examples: Invalid request parameters (')
        ->and(strlen($exception->getMessage()))->toBeLessThan(300)
        ->and($exception->getMessage())->toEndWith('...)')
        ->and($exception->duoMessage())->toBe("Invalid request\n <b>parameters</b>.")
        ->and(DuoApiException::fromResponse($bare)->getMessage())->toBe('Duo answered 400 to POST /admin/v1/examples: Operation failed')
        ->and(DuoApiException::fromResponse($bare)->messageDetail())->toBeNull();
});

it('says how long to wait when Duo limits the rate of calls and sends Retry-After', function () {
    $limited = DuoApiException::fromResponse(FakeDuo::send(new ExampleGetRequest('DU1'), MockResponse::make(['stat' => 'FAIL', 'code' => 42901, 'message' => 'Too many requests'], 429, ['Retry-After' => ' 30 '])));
    $unsaid = DuoApiException::fromResponse(FakeDuo::send(new ExampleGetRequest('DU1'), FakeDuo::fail(42901, 'Too many requests')));

    expect($limited)->toBeInstanceOf(RateLimitedException::class)
        ->and($limited->retryAfter())->toBe(30)
        ->and($unsaid)->toBeInstanceOf(RateLimitedException::class)
        ->and($unsaid->retryAfter())->toBeNull();
});
