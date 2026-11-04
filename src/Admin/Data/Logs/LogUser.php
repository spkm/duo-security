<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use spkm\DuoSecurity\Data\Data;

/**
 * The user of a log event.
 */
readonly class LogUser extends Data
{
    /**
     * @param  array<mixed>|null  $groups
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $key = null,
        public ?string $name = null,
        public ?array $groups = null,
        public array $extra = [],
    ) {}
}
