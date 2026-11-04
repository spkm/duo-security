<?php

use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\LazyCollection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use spkm\DuoSecurity\DuoLogPaginator;
use spkm\DuoSecurity\DuoPaginator;
use spkm\DuoSecurity\Exceptions\NotFoundException;
use spkm\DuoSecurity\Exceptions\ServerErrorException;
use spkm\DuoSecurity\Exceptions\UnauthorizedException;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Query\LogQuery;
use spkm\DuoSecurity\Tests\Fixtures\ExampleData;
use spkm\DuoSecurity\Tests\Fixtures\ExampleListRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleLogRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExamplePlainListRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

function exampleList(): ListQuery
{
    return new ListQuery(FakeDuo::admin(), ExampleData::class, fn (array $params) => new ExampleListRequest($params));
}

function exampleLog(): LogQuery
{
    return new LogQuery(FakeDuo::admin(), ExampleData::class, fn (array $params) => new ExampleLogRequest($params));
}

it('gets one page of a list, with the parameters, limit and offset asked for', function () {
    $mockClient = FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::page([['user_id' => 'DU1'], ['user_id' => 'DU2']], 2, 9)]);

    $page = exampleList()->withParameter('username', 'root')->limit(2)->skip(4)->get();

    expect($page->first())->toBeInstanceOf(ExampleData::class)
        ->and($page->count())->toBe(2)
        ->and(FakeDuo::queriesReceived($mockClient, ExampleListRequest::class))->toBe(['limit=2&offset=4&username=root']);
});

it('gives the first record of a list, asking for one, or null', function () {
    $mockClient = FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::page([['user_id' => 'DU1']]), ExamplePlainListRequest::class => FakeDuo::page([])]);

    $first = exampleList()->first();
    $none = (new ListQuery(FakeDuo::admin(), null, fn (array $params) => new ExamplePlainListRequest($params)))->first();

    expect($first->user_id)->toBe('DU1')
        ->and($none)->toBeNull()
        ->and(FakeDuo::queriesReceived($mockClient, ExampleListRequest::class))->toBe(['limit=1']);
});

it('pages through every record of a list, as models, lazily or all at once', function () {
    $mockClient = FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::listPages(5)]);

    $lazy = exampleList()->limit(2)->lazy();
    $ids = $lazy->map(fn (ExampleData $item) => $item->user_id)->all();
    $all = exampleList()->all();

    expect($lazy)->toBeInstanceOf(LazyCollection::class)
        ->and($ids)->toBe(['DU1', 'DU2', 'DU3', 'DU4', 'DU5'])
        ->and($all->count())->toBe(5)
        ->and($all->first())->toBeInstanceOf(ExampleData::class)
        ->and(FakeDuo::queriesReceived($mockClient, ExampleListRequest::class))->toBe(['limit=2&offset=0', 'limit=2&offset=2', 'limit=2&offset=4', 'limit=3&offset=0', 'limit=3&offset=3'])
        ->and(exampleList()->paginate(2))->toBeInstanceOf(DuoPaginator::class);
});

it('counts the records of a list from the total Duo gives, asking for one record', function () {
    $mockClient = FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::page([['user_id' => 'DU1']], 1, 42), ExamplePlainListRequest::class => FakeDuo::ok([['a' => 1], ['b' => 2]])]);

    expect(exampleList()->withParameter('email', 'x')->count())->toBe(42)
        ->and((new ListQuery(FakeDuo::admin(), null, fn (array $params) => new ExamplePlainListRequest($params)))->count())->toBe(2)
        ->and(FakeDuo::queriesReceived($mockClient, ExampleListRequest::class))->toBe(['email=x&limit=1']);
});

it('throws Duo\'s exception for a list it refused, and a server error for an answer that is not JSON', function () {
    FakeDuo::respondWith([ExampleListRequest::class => FakeDuo::fail(40401, 'Resource not found'), ExamplePlainListRequest::class => MockResponse::make('nope', 200)]);
    $plain = new ListQuery(FakeDuo::admin(), null, fn (array $params) => new ExamplePlainListRequest($params));

    expect(fn () => exampleList()->get())->toThrow(NotFoundException::class)
        ->and(fn () => exampleList()->count())->toThrow(NotFoundException::class)
        ->and(fn () => $plain->get())->toThrow(ServerErrorException::class)
        ->and(fn () => $plain->count())->toThrow(ServerErrorException::class);
});

it('queries a log between two times in milliseconds, sorted and filtered, one page or every page', function () {
    Carbon::setTestNow('2026-11-04 09:00:00');
    $mockClient = FakeDuo::respondWith([ExampleLogRequest::class => function (PendingRequest $request): MockResponse {
        return $request->query()->get('next_offset') === null
            ? FakeDuo::logPage('items', [['user_id' => 'DU2', 'isotimestamp' => '2026-11-04T08:00:00+00:00']], '1762243200000,tx', 2)
            : FakeDuo::logPage('items', [['user_id' => 'DU1']], null, 2);
    }]);
    $from = CarbonImmutable::parse('2026-11-03 09:00:00', 'UTC');

    $page = exampleLog()->between($from, $from->addDay())->oldestFirst()->withParameter('users', 'DU1')->limit(10)->get();
    $all = exampleLog()->since($from)->newestFirst()->all();
    $lazy = exampleLog()->between(1762160400000, 1762246800000)->sort('ts:asc')->lazy()->count();

    expect($page->first()->user_id)->toBe('DU2')
        ->and($page->first()->isotimestamp->hour)->toBe(8)
        ->and($all->map(fn (ExampleData $event) => $event->user_id)->all())->toBe(['DU2', 'DU1'])
        ->and($lazy)->toBe(2)
        ->and(exampleLog()->paginate())->toBeInstanceOf(DuoLogPaginator::class)
        ->and(FakeDuo::queriesReceived($mockClient, ExampleLogRequest::class))->toBe([
            'limit=10&maxtime=1793782800000&mintime=1793696400000&sort=ts%3Aasc&users=DU1',
            'limit=1000&maxtime=1793782680000&mintime=1793696400000&sort=ts%3Adesc',
            'limit=1000&maxtime=1793782680000&mintime=1793696400000&next_offset=1762243200000%2Ctx&sort=ts%3Adesc',
            'limit=1000&maxtime=1762246800000&mintime=1762160400000&sort=ts%3Aasc',
            'limit=1000&maxtime=1762246800000&mintime=1762160400000&next_offset=1762243200000%2Ctx&sort=ts%3Aasc',
        ]);
});

it('throws Duo\'s exception for a log it refused', function () {
    FakeDuo::respondWith([ExampleLogRequest::class => FakeDuo::fail(40301, 'Access forbidden'), ExamplePlainListRequest::class => MockResponse::make('nope', 200)]);

    expect(fn () => exampleLog()->get())->toThrow(UnauthorizedException::class)
        ->and(fn () => exampleLog()->all())->toThrow(UnauthorizedException::class);
});
