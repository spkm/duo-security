<?php

namespace spkm\DuoSecurity\Admin\Requests\RegisteredDevices;

use spkm\DuoSecurity\Admin\Data\RegisteredDevices\BlockedDevice;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve blocked registered devices: GET /admin/v1/registered_devices/blocked. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListBlockedRegisteredDevicesRequest extends ListRequest
{
    protected ?string $dto = BlockedDevice::class;

    protected int $maxPageSize = 500;

    protected bool $cacheable = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/registered_devices/blocked';
    }
}
