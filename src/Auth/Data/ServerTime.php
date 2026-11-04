<?php

namespace spkm\DuoSecurity\Auth\Data;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * Duo's answer to a ping or a check: its time.
 */
readonly class ServerTime extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?CarbonImmutable $time = null,
        public array $extra = [],
    ) {}
}
