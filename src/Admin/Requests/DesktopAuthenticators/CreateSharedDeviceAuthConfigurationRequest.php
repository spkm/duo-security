<?php

namespace spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators;

use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\SharedDeviceAuth;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create a shared device authentication configuration: POST /admin/v1/desktop_authenticators/shared_device_auth. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: group_id_list, trusted_endpoint_integration_id_list, active, name.
 */
class CreateSharedDeviceAuthConfigurationRequest extends WriteRequest
{
    protected ?string $dto = SharedDeviceAuth::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: group_id_list, trusted_endpoint_integration_id_list, active, name.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/desktop_authenticators/shared_device_auth';
    }
}
