<?php

namespace spkm\DuoSecurity\Admin\Requests\RegisteredDevices;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Unblock a registered device: DELETE /admin/v1/registered_devices/blocked/{compkey}. Requires the "Grant resource - Write" permission.
 */
class UnblockRegisteredDeviceRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $compkey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/registered_devices/blocked/'.Path::segment($this->compkey);
    }
}
