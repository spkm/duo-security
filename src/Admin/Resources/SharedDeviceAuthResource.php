<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\SharedDeviceAuth;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\CreateSharedDeviceAuthConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\DeleteSharedDeviceAuthConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\GetSharedDeviceAuthConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\ListSharedDeviceAuthConfigurationsRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\UpdateSharedDeviceAuthConfigurationRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Shared Device Authentication endpoints of the Admin API, from DuoSecurity::sharedDeviceAuth().
 */
class SharedDeviceAuthResource extends BaseResource
{
    /**
     * Retrieve the shared device authentication configurations: GET /admin/v1/desktop_authenticators/shared_device_auth.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<SharedDeviceAuth>
     */
    public function listSharedDeviceAuthConfigurations(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListSharedDeviceAuthConfigurationsRequest([...$params, ...$page]), SharedDeviceAuth::class);
    }

    /**
     * Retrieve a shared device authentication configuration: GET /admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getSharedDeviceAuthConfiguration(string $sharedDeviceKey, array $params = []): SharedDeviceAuth
    {
        return $this->fetch(new GetSharedDeviceAuthConfigurationRequest($sharedDeviceKey, $params));
    }

    /**
     * Create a shared device authentication configuration: POST /admin/v1/desktop_authenticators/shared_device_auth.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: group_id_list, trusted_endpoint_integration_id_list, active, name.
     */
    public function createSharedDeviceAuthConfiguration(array $params = []): SharedDeviceAuth
    {
        return $this->fetch(new CreateSharedDeviceAuthConfigurationRequest($params));
    }

    /**
     * Update a shared device authentication configuration: PUT /admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: active, group_id_list, name, trusted_endpoint_integration_id_list.
     */
    public function updateSharedDeviceAuthConfiguration(string $sharedDeviceKey, array $params = []): SharedDeviceAuth
    {
        return $this->fetch(new UpdateSharedDeviceAuthConfigurationRequest($sharedDeviceKey, $params));
    }

    /**
     * Delete a shared device authentication configuration: DELETE /admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteSharedDeviceAuthConfiguration(string $sharedDeviceKey, array $params = []): bool
    {
        return $this->perform(new DeleteSharedDeviceAuthConfigurationRequest($sharedDeviceKey, $params));
    }
}
