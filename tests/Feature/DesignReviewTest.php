<?php

use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use spkm\DuoSecurity\Admin\Data\Logs\AccessDevice;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\ListSubaccountsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\CreateUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\EnrollUserRequest;
use spkm\DuoSecurity\Auth\Requests\EnrollStatusRequest;
use spkm\DuoSecurity\Facades\DuoSecurity;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Query\LogQuery;
use spkm\DuoSecurity\Tests\Fixtures\ExampleData;
use spkm\DuoSecurity\Tests\Fixtures\ExampleListRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExamplePlainListRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleTimedLogRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

/*
 * What an independent design review of the package found, each pinned here so it stays fixed.
 */

it('counts a list by reading every page when Duo gives no total but says there is more', function () {
    $mockClient = FakeDuo::respondWith([ExamplePlainListRequest::class => function (PendingRequest $request): MockResponse {
        $offset = (int) $request->query()->get('offset', 0);

        return FakeDuo::ok(['events' => array_fill(0, min(2, 5 - $offset), ['a' => 1]), 'metadata' => $offset + 2 < 5 ? ['next_offset' => $offset + 2] : []]);
    }]);
    $query = new ListQuery(FakeDuo::admin(), null, fn (array $params) => new ExamplePlainListRequest($params));

    expect($query->count())->toBe(5)
        ->and(FakeDuo::responsesTo($mockClient, ExamplePlainListRequest::class))->toHaveCount(4);
});

it('counts a short list without a total from its one page', function () {
    FakeDuo::respondWith([ExamplePlainListRequest::class => FakeDuo::ok(['events' => [['a' => 1], ['b' => 2]]])]);

    expect((new ListQuery(FakeDuo::admin(), null, fn (array $params) => new ExamplePlainListRequest($params)))->count())->toBe(2);
});

it('starts paging from the offset skip() asked for', function () {
    $mockClient = FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::listPages(7)]);
    $query = new ListQuery(FakeDuo::admin(), ExampleData::class, fn (array $params) => new ExampleListRequest($params));

    $ids = $query->skip(4)->limit(2)->lazy()->map(fn (ExampleData $user) => $user->user_id)->all();

    expect($ids)->toBe(['DU5', 'DU6', 'DU7'])
        ->and(FakeDuo::queriesReceived($mockClient, ExampleListRequest::class))->toBe(['limit=2&offset=4', 'limit=2&offset=6']);
});

it('sends a v1 log only its mintime, and stops at the window end since() or between() gave', function () {
    $mockClient = FakeDuo::respondWith([ExampleTimedLogRequest::class => function (PendingRequest $request): MockResponse {
        $from = (int) $request->query()->get('mintime', 0);
        $events = [];

        for ($timestamp = max($from, 1000); $timestamp < min(1000 + 9, $from + 3); $timestamp++) {
            $events[] = ['timestamp' => $timestamp];
        }

        return FakeDuo::ok($events);
    }]);
    $query = new LogQuery(FakeDuo::admin(), null, fn (array $params) => new ExampleTimedLogRequest($params));

    $events = $query->between(1000, 1004)->limit(50)->all();

    expect(array_column($events->all(), 'timestamp'))->toBe([1000, 1001, 1002, 1003, 1004])
        ->and(FakeDuo::queriesReceived($mockClient, ExampleTimedLogRequest::class))->toBe(['mintime=1000', 'mintime=1003']);
});

it('falls back to the connection\'s Admin API hostname for its Auth API', function () {
    config()->set('duo-security.connections.other', [
        'admin' => ['integration_key' => 'DIOTHER', 'secret_key' => 'othersecret', 'api_hostname' => 'api-other.duosecurity.test'],
        'auth' => ['integration_key' => 'DIOTHERAUTH', 'secret_key' => 'othersecret'],
    ]);

    expect(DuoSecurity::authConnector('other')->resolveBaseUrl())->toBe('https://api-other.duosecurity.test');
});

it('retries a POST that only reads, as it says so, but never a write', function () {
    expect((new ListSubaccountsRequest)->isSafe())->toBeTrue()
        ->and((new EnrollStatusRequest('DU1', 'CODE'))->isSafe())->toBeTrue()
        ->and((new CreateUserRequest(['username' => 'x']))->isSafe())->toBeFalse()
        ->and((new EnrollUserRequest('x', 'x@example.test'))->isSafe())->toBeFalse();
});

it('reads a boolean Duo sends for a true/false/unknown flag as the word, not as "1" or ""', function () {
    $device = AccessDevice::from(['is_encryption_enabled' => true, 'is_firewall_enabled' => false, 'is_password_set' => 'unknown']);

    expect($device->is_encryption_enabled)->toBe('true')
        ->and($device->is_firewall_enabled)->toBe('false')
        ->and($device->is_password_set)->toBe('unknown');
});

it('gives the enrollment code and the enrollment status as strings', function () {
    FakeDuo::respondWith([EnrollUserRequest::class => FakeDuo::ok('ENROLLCODE'), EnrollStatusRequest::class => FakeDuo::ok('waiting')]);

    expect(FakeDuo::admin()->users()->enrollUser('jsmith', 'jsmith@example.test'))->toBe('ENROLLCODE')
        ->and(FakeDuo::auth()->auth()->enrollStatus('DU1', 'CODE'))->toBe('waiting');
});
