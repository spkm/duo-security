<?php

namespace spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators;

use Saloon\Enums\Method;
use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\SharedDeviceAuth;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Update a shared device authentication configuration: PUT /admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: active, group_id_list, name, trusted_endpoint_integration_id_list.
 */
class UpdateSharedDeviceAuthConfigurationRequest extends WriteRequest
{
    protected ?string $dto = SharedDeviceAuth::class;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: active, group_id_list, name, trusted_endpoint_integration_id_list.
     */
    public function __construct(protected string $sharedDeviceKey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/desktop_authenticators/shared_device_auth/'.Path::segment($this->sharedDeviceKey);
    }
}
