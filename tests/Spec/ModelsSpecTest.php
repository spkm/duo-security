<?php

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use spkm\DuoSecurity\Data\Data;

/**
 * Every model class in the package.
 *
 * @return list<class-string<Data>>
 */
function duoModels(): array
{
    $admin = collect(glob(__DIR__.'/../../src/Admin/Data/*/*.php'))
        ->map(fn (string $file) => 'spkm\\DuoSecurity\\Admin\\Data\\'.basename(dirname($file)).'\\'.basename($file, '.php'));
    $auth = collect(glob(__DIR__.'/../../src/Auth/Data/*.php'))
        ->map(fn (string $file) => 'spkm\\DuoSecurity\\Auth\\Data\\'.basename($file, '.php'));

    return $admin->merge($auth)->sort()->values()->all();
}

it('has a model for every record Duo documents, each a readonly Data with every property nullable and an extra bag', function () {
    $models = duoModels();

    expect($models)->not->toBeEmpty()->and(count($models))->toBeGreaterThan(60);

    foreach ($models as $class) {
        $reflection = new ReflectionClass($class);
        $parameters = $reflection->getConstructor()->getParameters();
        $last = end($parameters);

        expect($reflection->isSubclassOf(Data::class))->toBeTrue($class)
            ->and($reflection->isReadOnly())->toBeTrue($class)
            ->and($last->getName())->toBe('extra', $class);

        foreach ($parameters as $parameter) {
            expect($parameter->isDefaultValueAvailable())->toBeTrue("$class::\${$parameter->getName()} needs a default");
        }
    }
});

it('builds every model from a record with every field set, and writes it back', function () {
    foreach (duoModels() as $class) {
        $record = [];
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
            $name = $parameter->getName();
            $type = $parameter->getType();
            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : 'mixed';

            if ($name === 'extra') {
                continue;
            }

            $record[$name] = match ($typeName) {
                'string' => "$name value",
                'int' => 7,
                'float' => 1.5,
                'bool' => true,
                'array' => ['k' => 'v'],
                CarbonImmutable::class => 1757000000,
                Collection::class => [['id' => '1']],
                default => ['nested' => true],
            };
        }
        $record['something_new'] = 'kept';

        $model = $class::from($record);
        $array = $model->toArray(withHidden: true);

        expect($model)->toBeInstanceOf($class)
            ->and($model->extra)->toBe(['something_new' => 'kept'], $class)
            ->and($array['something_new'] ?? null)->toBe('kept', $class)
            ->and(json_encode($model))->toBeString();

        foreach ($record as $key => $value) {
            if (is_array($value) || $value === 1757000000) {
                continue;
            }

            expect($array[$key] ?? null)->toBe($value, "$class::$key");
        }

        expect($class::from([]))->toBeInstanceOf($class)
            ->and($class::collect([$record, $record]))->toHaveCount(2);
    }
});

it('reads Unix timestamps as dates and nested records as their models', function () {
    foreach (duoModels() as $class) {
        $reflection = new ReflectionClass($class);

        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
            $type = $parameter->getType();
            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : 'mixed';

            if ($typeName === CarbonImmutable::class) {
                $model = $class::from([$parameter->getName() => 1757000000]);

                expect($model->{$parameter->getName()})->toBeInstanceOf(CarbonImmutable::class)
                    ->and($model->{$parameter->getName()}->getTimestamp())->toBe(1757000000)
                    ->and($model->toArray()[$parameter->getName()])->toBe(1757000000);
            } elseif ($typeName !== 'mixed' && is_subclass_of($typeName, Data::class)) {
                $model = $class::from([$parameter->getName() => ['anything' => 'x']]);

                expect($model->{$parameter->getName()})->toBeInstanceOf($typeName)
                    ->and($model->{$parameter->getName()}->extra)->toBe(['anything' => 'x']);
            }
        }
    }
});
