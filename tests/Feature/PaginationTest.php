<?php

use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use spkm\DuoSecurity\Exceptions\NotFoundException;
use spkm\DuoSecurity\Exceptions\ServerErrorException;
use spkm\DuoSecurity\Tests\Fixtures\ExampleData;
use spkm\DuoSecurity\Tests\Fixtures\ExampleListRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleLogRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExamplePlainListRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleTimedLogRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

it('pages a list from an offset of 0, with the configured page size, until Duo gives no next offset', function () {
    config()->set('duo-security.pagination.per_page', 2);
    $mockClient = FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::listPages(5)]);

    $items = FakeDuo::admin()->paginate(new ExampleListRequest)->collect()->all();

    expect(array_column($items, 'user_id'))->toBe(['DU1', 'DU2', 'DU3', 'DU4', 'DU5'])
        ->and(FakeDuo::queriesReceived($mockClient, ExampleListRequest::class))->toBe(['limit=2&offset=0', 'limit=2&offset=2', 'limit=2&offset=4']);
});

it('asks for at most what the endpoint allows a page to hold', function () {
    $mockClient = FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::listPages(4)]);

    $items = FakeDuo::admin()->paginate(new ExampleListRequest, 50)->collect()->all();

    expect($items)->toHaveCount(4)
        ->and(FakeDuo::queriesReceived($mockClient, ExampleListRequest::class))->toBe(['limit=3&offset=0', 'limit=3&offset=3']);
});

it('gives every record as the request\'s model, or as the model asked for', function () {
    FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::listPages(2), ExamplePlainListRequest::class => FakeDuo::listPages(2)]);
    $connector = FakeDuo::admin();

    $models = $connector->paginate(new ExampleListRequest)->dtos()->all();
    $asked = $connector->paginate(new ExamplePlainListRequest)->models(ExampleData::class)->all();

    expect($models)->toHaveCount(2)
        ->and($models[0])->toBeInstanceOf(ExampleData::class)
        ->and($models[1]->username)->toBe('person2')
        ->and($asked[0])->toBeInstanceOf(ExampleData::class)
        ->and(fn () => $connector->paginate(new ExamplePlainListRequest)->dtos())->toThrow(LogicException::class);
});

it('stops at an empty page, and at a page Duo repeats', function () {
    $mockClient = FakeDuo::respondWith([
        ExampleListRequest::class => FakeDuo::page([], 3, 0),
        ExamplePlainListRequest::class => FakeDuo::page([['user_id' => 'DU1']], 1, 99),
    ]);
    $connector = FakeDuo::admin();

    expect($connector->paginate(new ExampleListRequest)->collect()->all())->toBe([])
        ->and($connector->paginate(new ExamplePlainListRequest)->collect()->all())->toBe([['user_id' => 'DU1']])
        ->and(FakeDuo::responsesTo($mockClient, ExampleListRequest::class))->toHaveCount(1)
        ->and(FakeDuo::responsesTo($mockClient, ExamplePlainListRequest::class))->toHaveCount(2);
});

it('throws Duo\'s exception for a page it refused, and a server error for one that is not JSON', function () {
    FakeDuo::respondWith([
        ExampleListRequest::class => FakeDuo::fail(40401, 'Resource not found'),
        ExamplePlainListRequest::class => MockResponse::make('<html>maintenance</html>', 200, ['Content-Type' => 'text/html']),
    ]);
    $connector = FakeDuo::admin();

    expect(fn () => $connector->paginate(new ExampleListRequest)->collect()->all())->toThrow(NotFoundException::class)
        ->and(fn () => $connector->paginate(new ExamplePlainListRequest)->collect()->all())->toThrow(ServerErrorException::class);
});

it('pages a v2 log by the next_offset cursor Duo gives, as a string or as a list, until a page has none', function () {
    $mockClient = FakeDuo::respondWith([ExampleLogRequest::class => function (PendingRequest $request): MockResponse {
        return match ($request->query()->get('next_offset')) {
            null => FakeDuo::logPage('items', [['user_id' => 'DU3']], '1666714065304,5bf1a860', 3),
            '1666714065304,5bf1a860' => FakeDuo::logPage('items', [['user_id' => 'DU2']], [1666714065000, '60799fee'], 3),
            '1666714065000,60799fee' => FakeDuo::logPage('items', [['user_id' => 'DU1']], null, 3),
            default => FakeDuo::fail(40002),
        };
    }]);

    $items = FakeDuo::admin()->paginate(new ExampleLogRequest(['mintime' => 1666700000000, 'maxtime' => 1666800000000]), 500)->dtos()->all();

    expect(array_map(fn (ExampleData $item) => $item->user_id, $items))->toBe(['DU3', 'DU2', 'DU1'])
        ->and(FakeDuo::queriesReceived($mockClient, ExampleLogRequest::class))->toBe([
            'limit=500&maxtime=1666800000000&mintime=1666700000000',
            'limit=500&maxtime=1666800000000&mintime=1666700000000&next_offset=1666714065304%2C5bf1a860',
            'limit=500&maxtime=1666800000000&mintime=1666700000000&next_offset=1666714065000%2C60799fee',
        ]);
});

it('stops a v2 log at a page or a cursor Duo repeats, rather than looping', function () {
    $mockClient = FakeDuo::respondWith([ExampleLogRequest::class => FakeDuo::logPage('items', [['user_id' => 'DU1']], 'same', 9)]);

    $items = FakeDuo::admin()->paginate(new ExampleLogRequest)->collect()->all();

    expect($items)->toHaveCount(1)
        ->and(FakeDuo::responsesTo($mockClient, ExampleLogRequest::class))->toHaveCount(2)
        ->and(FakeDuo::queriesReceived($mockClient, ExampleLogRequest::class))->toBe(['limit=1000', 'limit=1000&next_offset=same']);
});

it('pages a v1 log by time: from a second after the last event, until a page is short', function () {
    $mockClient = FakeDuo::respondWith([ExampleTimedLogRequest::class => function (PendingRequest $request): MockResponse {
        $from = (int) $request->query()->get('mintime', 0);
        $events = [];

        for ($timestamp = max($from, 1000); $timestamp < min(1000 + 7, $from + 3); $timestamp++) {
            $events[] = ['timestamp' => $timestamp, 'action' => 'admin_login'];
        }

        return FakeDuo::ok($events);
    }]);

    $events = FakeDuo::admin()->paginate(new ExampleTimedLogRequest(['mintime' => 1000]))->collect()->all();

    expect(array_column($events, 'timestamp'))->toBe([1000, 1001, 1002, 1003, 1004, 1005, 1006])
        ->and(FakeDuo::queriesReceived($mockClient, ExampleTimedLogRequest::class))->toBe(['mintime=1000', 'mintime=1003', 'mintime=1006']);
});

it('stops a v1 log whose events carry no time, or whose last time does not move', function () {
    $mockClient = FakeDuo::respondWith([ExampleTimedLogRequest::class => FakeDuo::ok([['timestamp' => 5, 'action' => 'a'], ['timestamp' => 5, 'action' => 'b'], ['timestamp' => 5, 'action' => 'c']])]);

    $events = FakeDuo::admin()->paginate(new ExampleTimedLogRequest)->collect()->all();

    expect($events)->toHaveCount(3)
        ->and(FakeDuo::queriesReceived($mockClient, ExampleTimedLogRequest::class))->toBe(['', 'mintime=6']);
});
