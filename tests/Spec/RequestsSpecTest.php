<?php

use Illuminate\Support\Collection;
use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\Requests\DuoRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

$endpoints = require __DIR__.'/endpoints.php';

it('sends the method and path Duo documents, signed, and maps the response', function (string $class, array $args, string $method, string $path, string $shape, ?string $dto, bool $hasBody, bool $paginatable) {
    /** @var DuoRequest $request */
    $request = new $class(...$args);

    expect($request)->toBeInstanceOf(DuoRequest::class)
        ->and($request->getMethod()->value)->toBe($method)
        ->and($request->resolveEndpoint())->toBe($path)
        ->and($request->dtoClass())->toBe($dto)
        ->and($request->responseShape())->toBe($shape)
        ->and($request instanceof Paginatable)->toBe($paginatable)
        ->and($request instanceof HasBody)->toBe($hasBody)
        ->and($request->isSafe())->toBe($method === 'GET');

    $sample = ['user_id' => 'DU1', 'name' => 'x'];
    $mockClient = FakeDuo::respondWith([$class => match ($shape) {
        'list' => FakeDuo::page([$sample]),
        'one' => FakeDuo::ok($sample),
        'raw' => FakeDuo::ok('a string'),
        'text' => MockResponse::make('PNG BYTES', 200, ['Content-Type' => 'image/png']),
        default => FakeDuo::ok(''),
    }]);
    $connector = str_starts_with($class, 'spkm\\DuoSecurity\\Auth\\') ? FakeDuo::auth() : FakeDuo::admin();
    $response = $connector->send($request);
    $result = $response->dto();
    $signed = $path !== '/auth/v2/ping';

    expect((string) $response->getPsrRequest()->getUri())->toStartWith(FakeDuo::BASE_URL.$path)
        ->and($response->getPsrRequest()->getHeaderLine('Authorization') !== '')->toBe($signed)
        ->and($signed ? $response->getPsrRequest()->getHeaderLine('Authorization') : FakeDuo::signatureFor($response))->toBe(FakeDuo::signatureFor($response))
        ->and($mockClient->getRecordedResponses())->not->toBeEmpty();

    if ($hasBody) {
        expect($response->getPsrRequest()->getHeaderLine('Content-Type'))->toBe('application/json')
            ->and((string) $response->getPsrRequest()->getBody())->toStartWith('{');
    } else {
        expect((string) $response->getPsrRequest()->getBody())->toBe('');
    }

    match ($shape) {
        'list' => $dto === null
            ? expect($result)->toBeInstanceOf(Collection::class)->toHaveCount(1)->and($result->first())->toBe($sample)
            : expect($result)->toBeInstanceOf(Collection::class)->toHaveCount(1)->and($result->first())->toBeInstanceOf($dto),
        'one' => $dto === null ? expect($result)->toBe($sample) : expect($result)->toBeInstanceOf($dto)->and($result)->toBeInstanceOf(Data::class),
        'raw' => expect($result)->toBe('a string'),
        'text' => expect($result)->toBe('PNG BYTES'),
        default => expect($result)->toBeNull(),
    };
})->with($endpoints);

it('covers every operation of the Admin API and the Auth API, with every request class listed once', function () use ($endpoints) {
    $classes = collect(glob(__DIR__.'/../../src/Admin/Requests/*/*Request.php'))
        ->map(fn (string $file) => 'spkm\\DuoSecurity\\Admin\\Requests\\'.basename(dirname($file)).'\\'.basename($file, '.php'))
        ->merge(collect(glob(__DIR__.'/../../src/Auth/Requests/*Request.php'))->map(fn (string $file) => 'spkm\\DuoSecurity\\Auth\\Requests\\'.basename($file, '.php')))
        ->sort()
        ->values();
    $listed = collect($endpoints)->map(fn (array $row) => $row[0])->sort()->values();

    expect($listed->all())->toBe($classes->all())
        ->and($listed->unique())->toHaveCount($listed->count())
        ->and($classes)->toHaveCount(184)
        ->and(collect($endpoints)->keys()->unique())->toHaveCount(184)
        ->and(collect($endpoints)->keys()->filter(fn (string $operation) => str_starts_with($operation, 'GET /auth/') || str_starts_with($operation, 'POST /auth/')))->toHaveCount(8);
});

it('documents every request in ENDPOINTS.md', function () use ($endpoints) {
    $markdown = file_get_contents(__DIR__.'/../../ENDPOINTS.md');

    foreach ($endpoints as $operation => $row) {
        [$method, $path] = explode(' ', $operation, 2);

        expect($markdown)->toContain('`'.class_basename($row[0]).'`')
            ->and($markdown)->toContain("| $method | `$path` |");
    }
});

it('marks a request for an endpoint Duo has deprecated', function () use ($endpoints) {
    $deprecated = collect($endpoints)->filter(fn (array $row) => str_contains((string) (new ReflectionClass($row[0]))->getDocComment(), '@deprecated Duo deprecated this endpoint.'));

    expect($deprecated->keys()->all())->toContain('GET /admin/v1/u2ftokens', 'GET /admin/v1/users/{user_id}/u2ftokens', 'GET /admin/v1/logs/administrator')
        ->and($deprecated->keys()->all())->not->toContain('GET /admin/v1/users', 'GET /admin/v2/logs/authentication');
});

it('lets no request change the bytes after the connector has signed them', function () use ($endpoints) {
    foreach ($endpoints as $row) {
        $method = new ReflectionMethod($row[0], 'handlePsrRequest');

        expect($method->getDeclaringClass()->getName())->toBe(Request::class, $row[0].' overrides handlePsrRequest');
    }
});
