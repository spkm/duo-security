<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * An administrator action from the v1 administrator log.
 */
readonly class AdministratorLogEvent extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?CarbonImmutable $timestamp = null,
        public ?CarbonImmutable $isotimestamp = null,
        public ?string $username = null,
        public ?string $action = null,
        public ?string $object = null,
        public ?string $description = null,
        public array $extra = [],
    ) {}
}
