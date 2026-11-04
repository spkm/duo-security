<?php

use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Admin\Requests\Groups\GetGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\GetPhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\ListPhonesRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\GetTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\ListTokensRequest;
use spkm\DuoSecurity\Admin\Requests\Users\GetUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUsersRequest;
use spkm\DuoSecurity\Exceptions\UnauthorizedException;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

it('finds a user by id, or gives null when Duo has none', function () {
    $mockClient = FakeDuo::respondWith([GetUserRequest::class => FakeDuo::ok(['user_id' => 'DU1', 'username' => 'root'])]);

    $user = FakeDuo::admin()->users()->find('DU1');

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->username)->toBe('root')
        ->and(FakeDuo::urisReceived($mockClient, GetUserRequest::class))->toBe([FakeDuo::BASE_URL.'/admin/v1/users/DU1']);

    FakeDuo::respondWith([GetUserRequest::class => FakeDuo::fail(40401, 'Resource not found')]);

    expect(FakeDuo::admin()->users()->find('DU404'))->toBeNull();

    FakeDuo::respondWith([GetUserRequest::class => FakeDuo::fail(40301, 'Access forbidden')]);

    expect(fn () => FakeDuo::admin()->users()->find('DU1'))->toThrow(UnauthorizedException::class);
});

it('finds a user by username or email, which Duo answers with one user or none', function () {
    $mockClient = FakeDuo::respondWith([ListUsersRequest::class => function ($request) {
        return $request->query()->get('username') === 'root' || $request->query()->get('email') === 'root@example.test'
            ? FakeDuo::page([['user_id' => 'DU1', 'username' => 'root']])
            : FakeDuo::page([]);
    }]);
    $users = FakeDuo::admin()->users();

    expect($users->findByUsername('root')?->user_id)->toBe('DU1')
        ->and($users->findByUsername('nobody'))->toBeNull()
        ->and($users->findByEmail('root@example.test')?->user_id)->toBe('DU1')
        ->and($users->findByEmail('nobody@example.test'))->toBeNull()
        ->and(FakeDuo::queriesReceived($mockClient, ListUsersRequest::class))->toBe([
            'limit=1&username=root',
            'limit=1&username=nobody',
            'email=root%40example.test&limit=1',
            'email=nobody%40example.test&limit=1',
        ]);
});

it('finds a phone by id or by number, a token by id or by type and serial, and a group by id', function () {
    $mockClient = FakeDuo::respondWith([
        GetPhoneRequest::class => FakeDuo::ok(['phone_id' => 'DP1', 'number' => '+441234567890']),
        ListPhonesRequest::class => FakeDuo::page([['phone_id' => 'DP1', 'number' => '+441234567890', 'extension' => '12']]),
        GetTokenRequest::class => FakeDuo::ok(['token_id' => 'DH1', 'serial' => '0123']),
        ListTokensRequest::class => FakeDuo::page([['token_id' => 'DH1', 'type' => 'h6', 'serial' => '0123']]),
        GetGroupRequest::class => FakeDuo::ok(['group_id' => 'DG1', 'name' => 'Staff']),
    ]);
    $connector = FakeDuo::admin();

    expect($connector->phones()->find('DP1'))->toBeInstanceOf(Phone::class)
        ->and($connector->phones()->findByNumber('+441234567890')?->phone_id)->toBe('DP1')
        ->and($connector->phones()->findByNumber('+441234567890', '12')?->extension)->toBe('12')
        ->and($connector->tokens()->find('DH1'))->toBeInstanceOf(Token::class)
        ->and($connector->tokens()->findBySerial('h6', '0123')?->token_id)->toBe('DH1')
        ->and($connector->groups()->find('DG1'))->toBeInstanceOf(Group::class)
        ->and(FakeDuo::queriesReceived($mockClient, ListPhonesRequest::class))->toBe(['limit=1&number=%2B441234567890', 'extension=12&limit=1&number=%2B441234567890'])
        ->and(FakeDuo::queriesReceived($mockClient, ListTokensRequest::class))->toBe(['limit=1&serial=0123&type=h6'])
        ->and(FakeDuo::urisReceived($mockClient, GetGroupRequest::class))->toBe([FakeDuo::BASE_URL.'/admin/v2/groups/DG1']);
});
