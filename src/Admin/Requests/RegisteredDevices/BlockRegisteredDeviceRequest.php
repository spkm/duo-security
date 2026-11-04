<?php

namespace spkm\DuoSecurity\Admin\Requests\RegisteredDevices;

use spkm\DuoSecurity\Admin\Data\RegisteredDevices\BlockedDevice;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Block a registered device: POST /admin/v1/registered_devices/blocked/{compkey}. Requires the "Grant resource - Write" permission.
 */
class BlockRegisteredDeviceRequest extends WriteRequest
{
    protected ?string $dto = BlockedDevice::class;

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
