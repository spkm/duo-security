<?php

namespace spkm\DuoSecurity;

use BadMethodCallException;
use InvalidArgumentException;
use spkm\DuoSecurity\Admin\AdminConnector;
use spkm\DuoSecurity\Auth\AuthConnector;
use spkm\DuoSecurity\Auth\Resources\AuthResource;
use spkm\DuoSecurity\Signing\Credentials;

/**
 * What the DuoSecurity facade resolves: the Admin API connector for the default account, built from
 * config('duo-security.admin') the first time it is needed, the Auth API connector from config('duo-security.auth'),
 * and the same for any named connections. Methods the service doesn't have go to the default Admin API
 * connector, so DuoSecurity::users() is the default account's users.
 *
 * @mixin AdminConnector
 */
class DuoSecurityService
{
    /** @var array<string, AdminConnector> */
    protected array $admin = [];

    /** @var array<string, AuthConnector> */
    protected array $auth = [];

    /**
     * The Admin API connector for another account, configured under duo-security.connections.{name}.admin.
     */
    public function connection(string $name): AdminConnector
    {
        return $this->admin($name);
    }

    /**
     * The Admin API connector: the default account's, or a named connection's.
     */
    public function admin(?string $connection = null): AdminConnector
    {
        return $this->admin[$connection ?? ''] ??= new AdminConnector($this->credentials('admin', $connection));
    }

    /**
     * The Auth API's endpoints (ping, check, preauth, auth and the rest) for the default account, or a named
     * connection's.
     */
    public function auth(?string $connection = null): AuthResource
    {
        return $this->authConnector($connection)->auth();
    }

    /**
     * The Auth API connector: the default account's, or a named connection's, for DuoSecurity::authConnector()->send().
     */
    public function authConnector(?string $connection = null): AuthConnector
    {
        return $this->auth[$connection ?? ''] ??= new AuthConnector($this->credentials('auth', $connection));
    }

    /**
     * @param  array<mixed>  $parameters
     */
    public function __call(string $method, array $parameters): mixed
    {
        $connector = $this->admin();

        if (method_exists($connector, $method)) {
            return $connector->{$method}(...$parameters);
        }

        throw new BadMethodCallException("Method {$method} does not exist.");
    }

    /**
     * The credentials config gives for one of the APIs of a connection.
     */
    private function credentials(string $api, ?string $connection): Credentials
    {
        $config = $this->config($api, $connection);
        $describe = ($connection === null ? 'The default Duo connection' : 'The Duo connection "'.$connection.'"').'\'s '.($api === 'admin' ? 'Admin API' : 'Auth API');
        $setting = fn (string $key): ?string => is_string($config[$key] ?? null) && $config[$key] !== '' ? $config[$key] : null;

        return new Credentials(
            $setting('integration_key') ?? throw new InvalidArgumentException("$describe needs an integration_key."),
            $setting('secret_key') ?? throw new InvalidArgumentException("$describe needs a secret_key."),
            $setting('api_hostname') ?? throw new InvalidArgumentException("$describe needs an api_hostname."),
        );
    }

    /**
     * @return array<mixed>
     */
    private function config(string $api, ?string $connection): array
    {
        if ($connection === null) {
            return (array) config("duo-security.$api");
        }

        $config = config("duo-security.connections.$connection");

        if (! is_array($config)) {
            throw new InvalidArgumentException('No Duo connection named "'.$connection.'" is configured in duo-security.connections.');
        }

        return (array) ($config[$api] ?? []);
    }
}
