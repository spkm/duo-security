<?php

namespace spkm\DuoSecurity\Admin\Requests\RegisteredDevices;

use spkm\DuoSecurity\Requests\DeleteRequest;

/**
 * Unblock registered devices (their registrations stay): DELETE /admin/v1/registered_devices/blocked. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: registered_device_key_list.
 */
class UnblockRegisteredDevicesRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: registered_device_key_list.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/registered_devices/blocked';
    }
}
