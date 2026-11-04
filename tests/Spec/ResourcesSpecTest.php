<?php

use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use spkm\DuoSecurity\Admin\Resources\HasResources;
use spkm\DuoSecurity\Auth\Resources\AuthResource;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Query\LogQuery;
use spkm\DuoSecurity\Requests\DuoRequest;
use spkm\DuoSecurity\Resources\BaseResource;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

$endpoints = require __DIR__.'/endpoints.php';

/**
 * The resource classes the Admin API connector exposes, by accessor.
 *
 * @return array<string, class-string<BaseResource>>
 */
function adminResources(): array
{
    $resources = [];

    foreach ((new ReflectionClass(HasResources::class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        $resources[$method->getName()] = $method->getReturnType()->getName();
    }

    return $resources;
}

it('has a resource for every section of the Admin API, reached from the connector', function () {
    $resources = adminResources();
    $connector = FakeDuo::admin();

    expect(count($resources))->toBe(25)
        ->and(array_keys($resources))->toContain('users', 'groups', 'phones', 'tokens', 'bypassCodes', 'integrations', 'policies', 'administrators', 'logs', 'settings', 'info', 'trustMonitor');

    foreach ($resources as $accessor => $class) {
        expect($connector->{$accessor}())->toBeInstanceOf($class)
            ->and($connector->{$accessor}())->toBeInstanceOf(BaseResource::class)
            ->and($connector->{$accessor}()->connector())->toBe($connector);
    }
});

it('has a method on a resource for every request, named as the request is', function () use ($endpoints) {
    $classes = collect($endpoints)->map(fn (array $row) => $row[0]);
    $methods = [];

    foreach ([...array_values(adminResources()), AuthResource::class] as $resource) {
        foreach ((new ReflectionClass($resource))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // The finders (find(), findByUsername()...) come from the Concerns traits, beside the endpoints' methods.
            if ($method->getDeclaringClass()->getName() === $resource && ! str_contains((string) $method->getFileName(), '/Concerns/')) {
                $methods[] = lcfirst(class_basename($resource)).'::'.$method->getName();
            }
        }
    }

    foreach ($classes as $class) {
        $expected = lcfirst(substr(class_basename($class), 0, -strlen('Request')));

        expect(collect($methods)->filter(fn (string $method) => str_ends_with($method, '::'.$expected)))->toHaveCount(1, "$class has no single resource method $expected()");
    }

    expect(count($methods))->toBe(count($classes));
});

it('calls every resource method against a faked Duo, each sending its request', function () use ($endpoints) {
    $byClass = collect($endpoints)->keyBy(fn (array $row) => $row[0]);

    foreach ([...array_values(adminResources()), AuthResource::class] as $resourceClass) {
        $connector = $resourceClass === AuthResource::class ? FakeDuo::auth() : FakeDuo::admin();
        $resource = new $resourceClass($connector);

        foreach ((new ReflectionClass($resourceClass))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->getDeclaringClass()->getName() !== $resourceClass) {
                continue;
            }

            $arguments = [];

            foreach ($method->getParameters() as $parameter) {
                $arguments[] = $parameter->getType()?->getName() === 'array' ? ['limit' => 2] : 'ID1';
            }

            $returns = $method->getReturnType()?->getName();
            $mockClient = FakeDuo::respondWith([FakeDuo::BASE_URL.'/*' => match (true) {
                $returns === ListQuery::class, $returns === LogQuery::class, $returns === Collection::class => FakeDuo::page([['user_id' => 'DU1']]),
                $returns === 'string' => MockResponse::make('PNG', 200, ['Content-Type' => 'image/png']),
                $returns === 'mixed' => FakeDuo::ok('string answer'),
                $returns === 'bool' => FakeDuo::ok(''),
                default => FakeDuo::ok(['user_id' => 'DU1']),
            }]);

            $result = $resource->{$method->getName()}(...$arguments);

            if ($result instanceof ListQuery || $result instanceof LogQuery) {
                $result = $result->get();
            }

            $recorded = $mockClient->getRecordedResponses();

            expect($recorded)->toHaveCount(1, "$resourceClass::{$method->getName()}()")
                ->and($recorded[0]->getRequest())->toBeInstanceOf(DuoRequest::class)
                ->and($byClass->has($recorded[0]->getRequest()::class))->toBeTrue()
                ->and($result)->not->toBeNull("$resourceClass::{$method->getName()}()");

            $query = $recorded[0]->getPsrRequest()->getUri()->getQuery();

            if ($returns === ListQuery::class || $returns === LogQuery::class) {
                expect($query)->toContain('limit=2');
            }
        }
    }
});
