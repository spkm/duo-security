<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use spkm\DuoSecurity\Data\Data;

/**
 * The device that answered the second factor.
 */
readonly class AuthDevice extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $key = null,
        public ?string $name = null,
        public ?string $ip = null,
        public ?Location $location = null,
        public array $extra = [],
    ) {}
}
