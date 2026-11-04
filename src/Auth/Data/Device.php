<?php

namespace spkm\DuoSecurity\Auth\Data;

use spkm\DuoSecurity\Data\Data;

/**
 * A device a user can authenticate with, and the factors (capabilities) it supports.
 */
readonly class Device extends Data
{
    /**
     * @param  array<mixed>|null  $capabilities
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $device = null,
        public ?string $type = null,
        public ?array $capabilities = null,
        public ?string $display_name = null,
        public ?string $name = null,
        public ?string $number = null,
        public ?string $sms_nextcode = null,
        public array $extra = [],
    ) {}
}
