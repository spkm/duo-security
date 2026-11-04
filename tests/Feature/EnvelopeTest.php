<?php

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use spkm\DuoSecurity\Support\Envelope;
use spkm\DuoSecurity\Support\Path;
use spkm\DuoSecurity\Support\Payload;
use spkm\DuoSecurity\Support\QueryString;
use spkm\DuoSecurity\Tests\Fixtures\ExampleBinaryRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleChild;
use spkm\DuoSecurity\Tests\Fixtures\ExampleData;
use spkm\DuoSecurity\Tests\Fixtures\ExampleDeleteRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleGetRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleListRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleListWriteRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleLogRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExamplePlainListRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleRawWriteRequest;
use spkm\DuoSecurity\Tests\Fixtures\ExampleWriteRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

it('reads the one record of an answer as the request\'s model, the first of a one-element list too', function () {
    $object = FakeDuo::send(new ExampleGetRequest('DU1'), ['user_id' => 'DU1', 'username' => 'root', 'created' => 1757000000, 'phones' => []]);
    $list = FakeDuo::send(new ExampleGetRequest('DU1'), [['user_id' => 'DU2']]);
    $nothing = FakeDuo::send(new ExampleGetRequest('DU1'), '');

    expect($object->dto())->toBeInstanceOf(ExampleData::class)
        ->and($object->dto()->username)->toBe('root')
        ->and($object->dto()->created)->toBeInstanceOf(CarbonImmutable::class)
        ->and($object->dto()->created->getTimestamp())->toBe(1757000000)
        ->and($object->dto()->extra)->toBe(['phones' => []])
        ->and($list->dto()->user_id)->toBe('DU2')
        ->and($nothing->dto())->toBeNull();
});

it('reads a list of records as models, a list of strings as strings, and a nested list by its key', function () {
    $models = FakeDuo::send(new ExampleListRequest, FakeDuo::page([['user_id' => 'DU1'], ['user_id' => 'DU2']]));
    $strings = FakeDuo::send(new ExampleListWriteRequest('DU1', ['count' => 2]), ['123456789', '987654321']);
    $nested = FakeDuo::send(new ExampleLogRequest, FakeDuo::logPage('items', [['user_id' => 'DU3']], 'cursor', 1));
    $plain = FakeDuo::send(new ExamplePlainListRequest, [['a' => 1]]);
    $empty = FakeDuo::send(new ExampleListRequest, '');

    expect($models->dto())->toBeInstanceOf(Collection::class)
        ->and($models->dto()->first())->toBeInstanceOf(ExampleData::class)
        ->and($models->dto()->last()->user_id)->toBe('DU2')
        ->and($strings->dto()->all())->toBe(['123456789', '987654321'])
        ->and($nested->dto()->first()->user_id)->toBe('DU3')
        ->and($plain->dto()->all())->toBe([['a' => 1]])
        ->and($empty->dto()->all())->toBe([]);
});

it('reads a raw answer as Duo decoded it, nothing for an operation, and bytes for a file', function () {
    $raw = FakeDuo::send(new ExampleRawWriteRequest(['username' => 'root']), 'ENROLLCODE');
    $none = FakeDuo::send(new ExampleDeleteRequest('DU1'), '');
    $file = FakeDuo::send(new ExampleBinaryRequest, MockResponse::make("\x89PNG...", 200, ['Content-Type' => 'image/png']));

    expect($raw->dto())->toBe('ENROLLCODE')
        ->and($none->dto())->toBeNull()
        ->and($file->dto())->toBe("\x89PNG...")
        ->and($file->getPsrRequest()->getHeaderLine('Accept'))->toBe('*/*');
});

it('reads the metadata beside the response, or inside it, and the next offset in its forms', function () {
    $beside = FakeDuo::send(new ExampleListRequest, FakeDuo::page([['a' => 1]], 100, 250));
    $inside = FakeDuo::send(new ExampleLogRequest, FakeDuo::logPage('authlogs', [['a' => 1]], ['1666714065304', 'txid'], 7));
    $digits = FakeDuo::send(new ExampleListRequest, FakeDuo::page([], '300'));
    $last = FakeDuo::send(new ExampleListRequest, FakeDuo::page([['a' => 1]]));
    $plain = FakeDuo::send(new ExampleListRequest, [['a' => 1]]);

    expect(Envelope::metadata($beside))->toBe(['total_objects' => 250, 'next_offset' => 100])
        ->and(Envelope::nextOffset($beside))->toBe(100)
        ->and(Envelope::total($beside))->toBe(250)
        ->and(Envelope::items($inside))->toBe([['a' => 1]])
        ->and(Envelope::nextOffset($inside))->toBe('1666714065304,txid')
        ->and(Envelope::total($inside))->toBe(7)
        ->and(Envelope::nextOffset($digits))->toBe(300)
        ->and(Envelope::nextOffset($last))->toBeNull()
        ->and(Envelope::metadata($plain))->toBe([])
        ->and(Envelope::total($plain))->toBeNull()
        ->and(Envelope::succeeded($plain))->toBeTrue()
        ->and(Envelope::succeeded(FakeDuo::send(new ExampleListRequest, FakeDuo::fail(40002))))->toBeFalse()
        ->and(Envelope::succeeded(FakeDuo::send(new ExampleListRequest, MockResponse::make('nope', 200))))->toBeFalse()
        ->and(Envelope::items(FakeDuo::send(new ExampleListRequest, ['single' => 'object'])))->toBe([['single' => 'object']]);
});

it('writes a model from Duo\'s record and back, keeping what it has no property for', function () {
    $record = ['user_id' => 'DU1', 'username' => 'root', 'count' => '3', 'ratio' => '0.5', 'is_enrolled' => 'true', 'created' => 1757000000, 'isotimestamp' => '2023-03-21T22:34:49.466370+00:00', 'phone' => ['phone_id' => 'DP1', 'number' => '+441234', 'type' => 'mobile'], 'children' => [['phone_id' => 'DP2']], 'tags' => ['a'], 'secret_key' => 'shh', 'lockout_reason' => null, 'alias1' => 'r'];
    $model = ExampleData::from($record);

    expect($model->count)->toBe(3)
        ->and($model->ratio)->toBe(0.5)
        ->and($model->is_enrolled)->toBeTrue()
        ->and($model->created->toIso8601String())->toBe('2025-09-04T15:33:20+00:00')
        ->and($model->isotimestamp->year)->toBe(2023)
        ->and($model->phone)->toBeInstanceOf(ExampleChild::class)
        ->and($model->phone->extra)->toBe(['type' => 'mobile'])
        ->and($model->children->first())->toBeInstanceOf(ExampleChild::class)
        ->and($model->extra)->toBe(['lockout_reason' => null, 'alias1' => 'r'])
        ->and($model->toArray())->toBe(['lockout_reason' => null, 'alias1' => 'r', 'user_id' => 'DU1', 'username' => 'root', 'count' => 3, 'ratio' => 0.5, 'is_enrolled' => true, 'created' => 1757000000, 'isotimestamp' => 1679438089, 'phone' => ['type' => 'mobile', 'phone_id' => 'DP1', 'number' => '+441234'], 'children' => [['phone_id' => 'DP2']], 'tags' => ['a']])
        ->and($model->toArray(withHidden: true))->toHaveKey('secret_key', 'shh')
        ->and(json_encode($model))->not->toContain('shh')
        ->and(ExampleData::collect([['user_id' => 'DU9']])->first()->user_id)->toBe('DU9')
        ->and(ExampleData::dateFormat())->toBe('U');
});

it('writes parameters as Duo reads them: lists as JSON strings, dates as timestamps, nulls left out', function () {
    $date = CarbonImmutable::parse('2026-11-04 09:00:00.250', 'UTC');

    expect(Payload::parameters(['user_id_list' => ['DU1', 'DU2'], 'username_list' => '["a"]', 'mintime' => $date, 'maxtime' => $date, 'absent' => null, 'count' => 1], ['mintime']))
        ->toBe(['user_id_list' => '["DU1","DU2"]', 'username_list' => '["a"]', 'mintime' => 1793782800250, 'maxtime' => 1793782800, 'count' => 1])
        ->and(Payload::parameters(ExampleData::from(['user_id' => 'DU1', 'secret_key' => 'x'])))->toBe(['user_id' => 'DU1'])
        ->and(Payload::parameters(['phone' => ExampleChild::from(['phone_id' => 'DP1'])]))->toBe(['phone' => ['phone_id' => 'DP1']])
        ->and(Payload::jsonList([]))->toBe('[]')
        ->and(Payload::timestamp(5))->toBe(5)
        ->and(Payload::timestamp($date, true))->toBe(1793782800250);
});

it('encodes a path segment and refuses one that would change the endpoint', function () {
    expect(Path::segment('DU1 2/x'))->toBe('DU1%202%2Fx')
        ->and(fn () => Path::segment(''))->toThrow(InvalidArgumentException::class)
        ->and(fn () => Path::segment('..'))->toThrow(InvalidArgumentException::class)
        ->and(Path::api('admin/v1/users?x=1'))->toBe('/admin/v1/users?x=1')
        ->and(fn () => Path::api('/admin/./v1'))->toThrow(InvalidArgumentException::class)
        ->and(QueryString::parse('a=1+2&b=%20&c=x=y&a=3'))->toBe(['a' => '3', 'b' => ' ', 'c' => 'x=y'])
        ->and(QueryString::encode(['b' => 'x y', 'a' => 1]))->toBe('a=1&b=x%20y');
});

it('caches the answer to a GET for records only when told to, per account', function () {
    config()->set('duo-security.cache.enabled', true);
    $mockClient = FakeDuo::respondWith([ExampleGetRequest::class => FakeDuo::ok(['user_id' => 'DU1']), ExampleWriteRequest::class => FakeDuo::ok(['user_id' => 'DU1'])]);
    $connector = FakeDuo::admin();

    $first = $connector->send(new ExampleGetRequest('DU1'));
    $second = $connector->send(new ExampleGetRequest('DU1'));
    $other = FakeDuo::admin('api-other.duosecurity.test')->send(new ExampleGetRequest('DU1'));
    $connector->send(new ExampleWriteRequest(['a' => 1]));
    $connector->send(new ExampleWriteRequest(['a' => 1]));

    expect($first->isCached())->toBeFalse()
        ->and($second->isCached())->toBeTrue()
        ->and($other->isCached())->toBeFalse()
        ->and(FakeDuo::responsesTo($mockClient, ExampleWriteRequest::class))->toHaveCount(2);
});
