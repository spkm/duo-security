<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * An offline enrollment event: a user provisioned for, or removed from, Duo's offline access.
 */
readonly class OfflineEnrollmentEvent extends Data
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
