<?php

namespace spkm\DuoSecurity\Admin\Requests\RegisteredDevices;

use spkm\DuoSecurity\Admin\Data\RegisteredDevices\RegisteredDevice;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve registered devices (Duo Desktop): GET /admin/v1/registered_devices. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListRegisteredDevicesRequest extends ListRequest
{
    protected ?string $dto = RegisteredDevice::class;

    protected int $maxPageSize = 500;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/registered_devices';
    }
}
