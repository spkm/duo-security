<?php

namespace spkm\DuoSecurity\Admin\Data\RegisteredDevices;

use spkm\DuoSecurity\Data\Data;

/**
 * A registered device that is blocked.
 */
readonly class BlockedDevice extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $compkey = null,
        public ?string $device_name = null,
        public ?string $device_os = null,
        public ?string $identifier = null,
        public ?bool $blocked = null,
        public array $extra = [],
    ) {}
}
