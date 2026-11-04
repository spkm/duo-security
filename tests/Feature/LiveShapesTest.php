<?php

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Admin\Data\BypassCodes\BypassCode;
use spkm\DuoSecurity\Admin\Data\Info\AccountSummary;
use spkm\DuoSecurity\Admin\Data\Info\DashboardSummary;
use spkm\DuoSecurity\Admin\Data\Integrations\Integration;
use spkm\DuoSecurity\Query\LogQuery;
use spkm\DuoSecurity\Support\Payload;
use spkm\DuoSecurity\Tests\Fixtures\ExampleData;
use spkm\DuoSecurity\Tests\Fixtures\ExampleLogRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

/*
 * What a real Duo account answered that its documentation doesn't say: fields its records carry, and what its
 * endpoints refuse. Each found by running the package against a live account, read-only.
 */

it('takes a time in seconds for a parameter Duo wants in milliseconds, as a 10-digit number can only be seconds', function () {
    $seconds = 1762160400;

    expect(Payload::timestamp($seconds, milliseconds: true))->toBe(1762160400000)
        ->and(Payload::timestamp(1762160400000, milliseconds: true))->toBe(1762160400000)
        ->and(Payload::timestamp($seconds))->toBe($seconds)
        ->and(Payload::timestamp(CarbonImmutable::createFromTimestamp($seconds), milliseconds: true))->toBe(1762160400000)
        ->and(Payload::parameters(['mintime' => $seconds, 'maxtime' => 1762246800000, 'limit' => 10], ['mintime', 'maxtime']))->toBe(['mintime' => 1762160400000, 'maxtime' => 1762246800000, 'limit' => 10]);
});

it('sends a v2 log window given in seconds as milliseconds, as Duo refuses seconds', function () {
    $mockClient = FakeDuo::respondWith([ExampleLogRequest::class => FakeDuo::logPage('items', [], null, 0)]);
    $query = new LogQuery(FakeDuo::admin(), ExampleData::class, fn (array $params) => new ExampleLogRequest($params));

    $query->between(1762160400, 1762246800)->get();
    $query->between(1762160400000, 1762246800000)->get();

    expect(FakeDuo::queriesReceived($mockClient, ExampleLogRequest::class))->toBe([
        'maxtime=1762246800000&mintime=1762160400000',
        'maxtime=1762246800000&mintime=1762160400000',
    ]);
});

it('has a property for the fields Duo sends beyond its documentation', function (string $class, string $field, mixed $value) {
    $model = $class::from([$field => $value]);

    expect(property_exists($class, $field))->toBeTrue("$class has no $field")
        ->and($model->{$field})->toBe($value)
        ->and($model->extra)->toBe([]);
})->with([
    [AccountSummary::class, 'account_id', 'DAXXXXXXXXXXXXXXXXXX'],
    [DashboardSummary::class, 'account_id', 'DAXXXXXXXXXXXXXXXXXX'],
    [BypassCode::class, 'endpoint_verification', false],
    [Integration::class, 'os_logon_pwl_enabled', false],
    [Integration::class, 'visual_style', 'default'],
]);
