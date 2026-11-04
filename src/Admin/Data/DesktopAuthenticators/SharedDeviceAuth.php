<?php

namespace spkm\DuoSecurity\Admin\Data\DesktopAuthenticators;

use spkm\DuoSecurity\Data\Data;

/**
 * A shared device authentication configuration: the groups it applies to and the trusted endpoint integrations it uses.
 */
readonly class SharedDeviceAuth extends Data
{
    /**
     * @param  array<mixed>|null  $groups
     * @param  array<mixed>|null  $trusted_endpoint_integrations
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $shared_device_key = null,
        public ?string $name = null,
        public ?bool $active = null,
        public ?array $groups = null,
        public ?array $trusted_endpoint_integrations = null,
        public array $extra = [],
    ) {}
}
