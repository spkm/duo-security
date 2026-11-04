<?php

namespace spkm\DuoSecurity\Admin\Requests\RegisteredDevices;

use spkm\DuoSecurity\Admin\Data\RegisteredDevices\BlockedDevice;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a blocked registered device: GET /admin/v1/registered_devices/blocked/{compkey}. Requires the "Grant resource - Read" permission.
 */
class GetBlockedRegisteredDeviceRequest extends GetRequest
{
    protected ?string $dto = BlockedDevice::class;

    protected bool $cacheable = true;

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
