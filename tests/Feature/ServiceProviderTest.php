<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;
use spkm\DuoSecurity\Admin\AdminConnector;
use spkm\DuoSecurity\Auth\AuthConnector;
use spkm\DuoSecurity\Auth\Resources\AuthResource;
use spkm\DuoSecurity\DuoSecurityService;
use spkm\DuoSecurity\DuoSecurityServiceProvider;
use spkm\DuoSecurity\Facades\DuoSecurity;
use spkm\DuoSecurity\Tests\Fixtures\ExampleGetRequest;
use spkm\DuoSecurity\Tests\Helpers\FakeDuo;

/**
 * @param  array<string, mixed>  $admin
 * @param  array<string, mixed>|null  $auth
 */
function configureDuo(array $admin, ?array $auth = null): void
{
    config()->set('duo-security.admin', $admin);
    config()->set('duo-security.auth', $auth ?? ['integration_key' => null, 'secret_key' => null, 'api_hostname' => null]);
}

it('binds the service as a singleton behind the facade', function () {
    expect(app('duo-security'))->toBeInstanceOf(DuoSecurityService::class)
        ->and(app('duo-security'))->toBe(app('duo-security'))
        ->and(DuoSecurity::getFacadeRoot())->toBe(app('duo-security'));
});

it('loads the config with its environment names', function () {
    expect(config('duo-security'))->toHaveKeys(['admin', 'auth', 'connections', 'timeouts', 'retries', 'cache', 'pagination'])
        ->and(config('duo-security.admin'))->toHaveKeys(['integration_key', 'secret_key', 'api_hostname'])
        ->and(config('duo-security.auth'))->toHaveKeys(['integration_key', 'secret_key', 'api_hostname'])
        ->and(config('duo-security.pagination.per_page'))->toBe(100)
        ->and(config('duo-security.retries.interval_ms'))->toBe(1000)
        ->and(config('duo-security.connections'))->toBe([])
        ->and(config('duo-security.cache.enabled'))->toBeFalse()
        ->and(file_get_contents(__DIR__.'/../../config/duo-security.php'))->toContain(
            "env('DUO_INTEGRATION_KEY')",
            "env('DUO_SECRET_KEY')",
            "env('DUO_API_HOSTNAME')",
            "env('DUO_AUTH_INTEGRATION_KEY')",
            "env('DUO_AUTH_SECRET_KEY')",
            "env('DUO_AUTH_API_HOSTNAME', env('DUO_API_HOSTNAME'))",
            "env('DUO_CONNECT_TIMEOUT', 10)",
            "env('DUO_REQUEST_TIMEOUT', 30)",
            "env('DUO_RETRIES')",
            "env('DUO_CACHE', false)",
            "env('DUO_CACHE_STORE')",
        );
});

it('registers the install command and the config to publish', function () {
    $paths = ServiceProvider::pathsToPublish(DuoSecurityServiceProvider::class, 'duo-security-config');

    expect(Artisan::all())->toHaveKey('duo-security:install')
        ->and($paths)->toHaveCount(1)
        ->and(realpath((string) array_key_first($paths)))->toBe(realpath(__DIR__.'/../../config/duo-security.php'))
        ->and(array_values($paths)[0])->toBe(config_path('duo-security.php'));
});

it('builds the Admin API connector from config once, and sends through it from the facade', function () {
    configureDuo(['integration_key' => FakeDuo::INTEGRATION_KEY, 'secret_key' => FakeDuo::SECRET_KEY, 'api_hostname' => 'https://'.FakeDuo::HOST.'/']);
    $mockClient = FakeDuo::respondWith([ExampleGetRequest::class => FakeDuo::ok(['user_id' => 'DU1'])]);

    $response = DuoSecurity::send(new ExampleGetRequest('DU1'));

    expect(DuoSecurity::admin())->toBeInstanceOf(AdminConnector::class)
        ->and(DuoSecurity::admin())->toBe(DuoSecurity::admin())
        ->and(DuoSecurity::credentials()->identity())->toBe(FakeDuo::INTEGRATION_KEY)
        ->and(DuoSecurity::admin()->resolveBaseUrl())->toBe(FakeDuo::BASE_URL)
        ->and(FakeDuo::urisReceived($mockClient, ExampleGetRequest::class))->toBe([FakeDuo::BASE_URL.'/admin/v1/examples/DU1'])
        ->and($response->getPsrRequest()->getHeaderLine('Authorization'))->toBe(FakeDuo::signatureFor($response));
});

it('builds the Auth API connector from its own keys, with the admin hostname unless given one', function () {
    configureDuo(
        ['integration_key' => FakeDuo::INTEGRATION_KEY, 'secret_key' => FakeDuo::SECRET_KEY, 'api_hostname' => FakeDuo::HOST],
        ['integration_key' => 'DIAUTHKEY', 'secret_key' => 'authsecret', 'api_hostname' => FakeDuo::HOST],
    );

    expect(DuoSecurity::authConnector())->toBeInstanceOf(AuthConnector::class)
        ->and(DuoSecurity::authConnector())->toBe(DuoSecurity::authConnector())
        ->and(DuoSecurity::authConnector()->credentials()->identity())->toBe('DIAUTHKEY')
        ->and(DuoSecurity::authConnector()->resolveBaseUrl())->toBe(FakeDuo::BASE_URL)
        ->and(DuoSecurity::auth())->toBeInstanceOf(AuthResource::class)
        ->and(DuoSecurity::auth()->connector())->toBe(DuoSecurity::authConnector());
});

it('builds a named connection\'s connectors from duo-security.connections', function () {
    configureDuo(['integration_key' => null, 'secret_key' => null, 'api_hostname' => null]);
    config()->set('duo-security.connections.other', [
        'admin' => ['integration_key' => 'DIOTHER', 'secret_key' => 'othersecret', 'api_hostname' => 'api-other.duosecurity.test'],
        'auth' => ['integration_key' => 'DIOTHERAUTH', 'secret_key' => 'othersecret', 'api_hostname' => 'api-other.duosecurity.test'],
    ]);

    expect(DuoSecurity::connection('other')->credentials()->identity())->toBe('DIOTHER')
        ->and(DuoSecurity::admin('other'))->toBe(DuoSecurity::connection('other'))
        ->and(DuoSecurity::authConnector('other')->credentials()->identity())->toBe('DIOTHERAUTH')
        ->and(DuoSecurity::auth('other')->connector()->resolveBaseUrl())->toBe('https://api-other.duosecurity.test')
        ->and(fn () => DuoSecurity::connection('missing'))->toThrow(InvalidArgumentException::class, 'No Duo connection named "missing"');
});

it('says which key is missing from config', function () {
    configureDuo(['integration_key' => '', 'secret_key' => 's', 'api_hostname' => FakeDuo::HOST]);
    expect(fn () => DuoSecurity::admin())->toThrow(InvalidArgumentException::class, "The default Duo connection's Admin API needs an integration_key.");

    configureDuo(['integration_key' => 'i', 'secret_key' => null, 'api_hostname' => FakeDuo::HOST]);
    expect(fn () => DuoSecurity::admin())->toThrow(InvalidArgumentException::class, 'needs a secret_key.');

    configureDuo(['integration_key' => 'i', 'secret_key' => 's', 'api_hostname' => null]);
    expect(fn () => DuoSecurity::admin())->toThrow(InvalidArgumentException::class, 'needs an api_hostname.');

    config()->set('duo-security.connections.other', ['admin' => ['integration_key' => 'i']]);
    expect(fn () => DuoSecurity::connection('other'))->toThrow(InvalidArgumentException::class, 'The Duo connection "other"\'s Admin API needs a secret_key.')
        ->and(fn () => DuoSecurity::authConnector())->toThrow(InvalidArgumentException::class, "The default Duo connection's Auth API needs an integration_key.");
});

it('refuses a call neither the service nor the connector has', function () {
    configureDuo(['integration_key' => 'i', 'secret_key' => 's', 'api_hostname' => FakeDuo::HOST]);

    expect(fn () => DuoSecurity::nothingLikeThis())->toThrow(BadMethodCallException::class);
});
