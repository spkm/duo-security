<?php

namespace spkm\DuoSecurity\Admin\Data\RegisteredDevices;

use spkm\DuoSecurity\Data\Data;

/**
 * A device registered with Duo Desktop, and the users it belongs to.
 */
readonly class RegisteredDevice extends Data
{
    /**
     * @param  array<mixed>|null  $user
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $compkey = null,
        public ?string $device_name = null,
        public ?string $device_os = null,
        public ?string $identifier = null,
        public ?array $user = null,
        public ?bool $blocked = null,
        public array $extra = [],
    ) {}
}
