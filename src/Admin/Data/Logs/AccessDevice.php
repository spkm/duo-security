<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use spkm\DuoSecurity\Data\Data;

/**
 * The device the user logged in from, and its health as Duo saw it (true, false or unknown).
 */
readonly class AccessDevice extends Data
{
    /**
     * @param  array<mixed>|null  $security_agents
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $epkey = null,
        public ?string $hostname = null,
        public ?string $ip = null,
        public ?string $browser = null,
        public ?string $browser_version = null,
        public ?string $flash_version = null,
        public ?string $java_version = null,
        public ?string $os = null,
        public ?string $os_version = null,
        public ?string $is_encryption_enabled = null,
        public ?string $is_firewall_enabled = null,
        public ?string $is_password_set = null,
        public ?Location $location = null,
        public ?array $security_agents = null,
        public array $extra = [],
    ) {}
}
