<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * An activity event from the v2 activity log: an administrator or configuration change, who made it and to what.
 */
readonly class ActivityLogEvent extends Data
{
    /**
     * @param  array<mixed>|null  $outcome
     * @param  array<mixed>|null  $old_target
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $activity_id = null,
        public ?string $akey = null,
        public ?CarbonImmutable $ts = null,
        public ?string $action = null,
        public ?array $outcome = null,
        public ?Actor $actor = null,
        public ?LogApplication $application = null,
        public ?Target $target = null,
        public ?array $old_target = null,
        public ?AccessDevice $access_device = null,
        public array $extra = [],
    ) {}
}
