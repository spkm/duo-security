<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use spkm\DuoSecurity\Data\Data;

/**
 * Who made an activity event happen.
 */
readonly class Actor extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $key = null,
        public ?string $name = null,
        public ?string $type = null,
        public ?string $details = null,
        public array $extra = [],
    ) {}
}
