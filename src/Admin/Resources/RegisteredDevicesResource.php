<?php

namespace spkm\DuoSecurity\Admin\Resources;

use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\RegisteredDevices\BlockedDevice;
use spkm\DuoSecurity\Admin\Data\RegisteredDevices\RegisteredDevice;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\BlockRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\BlockRegisteredDevicesRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\DeleteRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\GetBlockedRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\GetRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\ListBlockedRegisteredDevicesRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\ListRegisteredDevicesRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\UnblockRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\UnblockRegisteredDevicesRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Registered Devices endpoints of the Admin API, from DuoSecurity::registeredDevices().
 */
class RegisteredDevicesResource extends BaseResource
{
    /**
     * Retrieve registered devices (Duo Desktop): GET /admin/v1/registered_devices.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<RegisteredDevice>
     */
    public function listRegisteredDevices(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListRegisteredDevicesRequest([...$params, ...$page]), RegisteredDevice::class);
    }

    /**
     * Retrieve a registered device: GET /admin/v1/registered_devices/{compkey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getRegisteredDevice(string $compkey, array $params = []): RegisteredDevice
    {
        return $this->fetch(new GetRegisteredDeviceRequest($compkey, $params));
    }

    /**
     * Delete a device's registration: DELETE /admin/v1/registered_devices/{compkey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteRegisteredDevice(string $compkey, array $params = []): bool
    {
        return $this->perform(new DeleteRegisteredDeviceRequest($compkey, $params));
    }

    /**
     * Retrieve blocked registered devices: GET /admin/v1/registered_devices/blocked.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<BlockedDevice>
     */
    public function listBlockedRegisteredDevices(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListBlockedRegisteredDevicesRequest([...$params, ...$page]), BlockedDevice::class);
    }

    /**
     * Retrieve a blocked registered device: GET /admin/v1/registered_devices/blocked/{compkey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getBlockedRegisteredDevice(string $compkey, array $params = []): BlockedDevice
    {
        return $this->fetch(new GetBlockedRegisteredDeviceRequest($compkey, $params));
    }

    /**
     * Block registered devices: POST /admin/v1/registered_devices/blocked.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: registered_device_key_list.
     * @return Collection<int, BlockedDevice>
     */
    public function blockRegisteredDevices(array $params = []): Collection
    {
        return $this->fetch(new BlockRegisteredDevicesRequest($params));
    }

    /**
     * Block a registered device: POST /admin/v1/registered_devices/blocked/{compkey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function blockRegisteredDevice(string $compkey, array $params = []): BlockedDevice
    {
        return $this->fetch(new BlockRegisteredDeviceRequest($compkey, $params));
    }

    /**
     * Unblock registered devices (their registrations stay): DELETE /admin/v1/registered_devices/blocked.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: registered_device_key_list.
     */
    public function unblockRegisteredDevices(array $params = []): bool
    {
        return $this->perform(new UnblockRegisteredDevicesRequest($params));
    }

    /**
     * Unblock a registered device: DELETE /admin/v1/registered_devices/blocked/{compkey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function unblockRegisteredDevice(string $compkey, array $params = []): bool
    {
        return $this->perform(new UnblockRegisteredDeviceRequest($compkey, $params));
    }
}
