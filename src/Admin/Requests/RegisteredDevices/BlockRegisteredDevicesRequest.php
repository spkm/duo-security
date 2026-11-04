<?php

namespace spkm\DuoSecurity\Admin\Requests\RegisteredDevices;

use spkm\DuoSecurity\Admin\Data\RegisteredDevices\BlockedDevice;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Block registered devices: POST /admin/v1/registered_devices/blocked. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: registered_device_key_list.
 */
class BlockRegisteredDevicesRequest extends WriteRequest
{
    protected ?string $dto = BlockedDevice::class;

    protected string $responseShape = self::LIST;

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
