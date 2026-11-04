<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use spkm\DuoSecurity\Data\Data;

/**
 * Where a device was, as far as Duo could tell.
 */
readonly class Location extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $city = null,
        public ?string $state = null,
        public ?string $country = null,
        public array $extra = [],
    ) {}
}
