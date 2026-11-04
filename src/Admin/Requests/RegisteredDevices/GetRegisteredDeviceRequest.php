<?php

namespace spkm\DuoSecurity\Admin\Requests\RegisteredDevices;

use spkm\DuoSecurity\Admin\Data\RegisteredDevices\RegisteredDevice;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a registered device: GET /admin/v1/registered_devices/{compkey}. Requires the "Grant resource - Read" permission.
 */
class GetRegisteredDeviceRequest extends GetRequest
{
    protected ?string $dto = RegisteredDevice::class;

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
        return '/admin/v1/registered_devices/'.Path::segment($this->compkey);
    }
}
