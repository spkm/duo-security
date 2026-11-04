<?php

namespace spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a shared device authentication configuration: DELETE /admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}. Requires the "Grant resource - Write" permission.
 */
class DeleteSharedDeviceAuthConfigurationRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
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
