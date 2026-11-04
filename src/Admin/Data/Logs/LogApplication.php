<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use spkm\DuoSecurity\Data\Data;

/**
 * The application of a log event.
 */
readonly class LogApplication extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $key = null,
        public ?string $name = null,
        public ?string $type = null,
        public array $extra = [],
    ) {}
}
