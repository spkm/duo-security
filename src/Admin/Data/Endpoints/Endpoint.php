<?php

namespace spkm\DuoSecurity\Admin\Data\Endpoints;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * An endpoint (a computer or device Duo has seen), with its health and the user it belongs to.
 */
readonly class Endpoint extends Data
{
    /**
     * @param  array<mixed>|null  $browsers
     * @param  array<mixed>|null  $security_agents
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $epkey = null,
        public ?string $type = null,
        public ?string $model = null,
        public ?string $username = null,
        public ?string $email = null,
        public ?array $browsers = null,
        public ?string $device_name = null,
        public ?string $device_identifier = null,
        public ?string $device_identifier_type = null,
        public ?string $device_udid = null,
        public ?string $device_username = null,
        public ?string $device_username_type = null,
        public ?string $hardware_uuid = null,
        public ?string $machine_guid = null,
        public ?string $computer_sid = null,
        public ?string $cpu_id = null,
        public ?string $domain_sid = null,
        public ?string $disk_encryption_status = null,
        public ?string $firewall_status = null,
        public ?string $password_status = null,
        public ?string $os_family = null,
        public ?string $os_version = null,
        public ?string $os_build = null,
        public ?string $os_software_status = null,
        public ?array $security_agents = null,
        public ?string $trusted_endpoint = null,
        public ?string $health_app_client_version = null,
        public ?CarbonImmutable $health_data_last_collected = null,
        public ?CarbonImmutable $last_updated = null,
        public array $extra = [],
    ) {}
}
