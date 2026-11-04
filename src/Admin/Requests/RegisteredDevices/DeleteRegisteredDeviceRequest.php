<?php

namespace spkm\DuoSecurity\Admin\Requests\RegisteredDevices;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a device's registration: DELETE /admin/v1/registered_devices/{compkey}. Requires the "Grant resource - Write" permission.
 */
class DeleteRegisteredDeviceRequest extends DeleteRequest
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
        return '/admin/v1/registered_devices/'.Path::segment($this->compkey);
    }
}
