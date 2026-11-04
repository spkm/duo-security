<?php

use spkm\DuoSecurity\Admin\AdminConnector;
use spkm\DuoSecurity\Admin\Resources\HasResources;
use spkm\DuoSecurity\Admin\Resources\UsersResource;
use spkm\DuoSecurity\Auth\Resources\AuthResource;
use spkm\DuoSecurity\DuoSecurityService;
use spkm\DuoSecurity\Facades\DuoSecurity;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

function facadeDocblock(): string
{
    return (string) (new ReflectionClass(DuoSecurity::class))->getDocComment();
}

it('documents every resource on the facade', function () {
    $accessors = (new ReflectionClass(HasResources::class))->getMethods(ReflectionMethod::IS_PUBLIC);
    $docblock = facadeDocblock();

    foreach ($accessors as $method) {
        $returns = $method->getReturnType();

        expect($returns)->toBeInstanceOf(ReflectionNamedType::class)
            ->and($docblock)->toContain('@method static \\'.$returns->getName().' '.$method->getName().'()');
    }

    preg_match_all('/@method static \\\\spkm\\\\DuoSecurity\\\\Admin\\\\Resources\\\\/', $docblock, $documented);

    expect($accessors)->not->toBeEmpty()
        ->and($documented[0])->toHaveCount(count($accessors));
});

it('documents the connector\'s own calls on the facade, each of which exists', function () {
    $docblock = facadeDocblock();

    foreach (['admin', 'connection', 'auth', 'authConnector', 'send', 'paginate', 'get', 'post', 'put', 'delete', 'credentials'] as $name) {
        expect($docblock)->toMatch('/@method static \S+ '.$name.'\(/');
    }

    preg_match_all('/@method static .*? (\w+)\(/', $docblock, $methods);

    foreach ($methods[1] as $name) {
        expect(method_exists(DuoSecurityService::class, $name) || method_exists(AdminConnector::class, $name))->toBeTrue("DuoSecurity::$name() is documented but does not exist");
    }
});

it('reaches the default account\'s resources through the facade', function () {
    config()->set('duo-security.admin', ['integration_key' => FakeDuo::INTEGRATION_KEY, 'secret_key' => FakeDuo::SECRET_KEY, 'api_hostname' => FakeDuo::HOST]);
    config()->set('duo-security.auth', ['integration_key' => 'DIAUTH', 'secret_key' => 'auth-secret', 'api_hostname' => FakeDuo::HOST]);

    expect(DuoSecurity::users())->toBeInstanceOf(UsersResource::class)
        ->and(DuoSecurity::users()->connector())->toBe(DuoSecurity::admin())
        ->and(DuoSecurity::auth())->toBeInstanceOf(AuthResource::class)
        ->and(DuoSecurity::auth()->connector())->toBe(DuoSecurity::authConnector())
        ->and(DuoSecurity::admin()->resolveBaseUrl())->toBe(FakeDuo::BASE_URL);
});
