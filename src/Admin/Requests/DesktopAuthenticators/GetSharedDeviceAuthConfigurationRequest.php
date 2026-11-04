<?php

namespace spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators;

use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\SharedDeviceAuth;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a shared device authentication configuration: GET /admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}. Requires the "Grant resource - Read" permission.
 */
class GetSharedDeviceAuthConfigurationRequest extends GetRequest
{
    protected ?string $dto = SharedDeviceAuth::class;

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
