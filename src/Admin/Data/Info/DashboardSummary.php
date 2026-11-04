<?php

namespace spkm\DuoSecurity\Admin\Data\Info;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * The Admin Panel dashboard's summary for a period: users, devices, methods, attempts and failure reasons.
 */
readonly class DashboardSummary extends Data
{
    /**
     * @param  array<mixed>|null  $user_summary
     * @param  array<mixed>|null  $device_summary
     * @param  array<mixed>|null  $authentication_methods
     * @param  array<mixed>|null  $authentication_attempts
     * @param  array<mixed>|null  $authentication_failure_reasons
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $account_id = null,
        public ?CarbonImmutable $mintime = null,
        public ?CarbonImmutable $maxtime = null,
        public ?array $user_summary = null,
        public ?array $device_summary = null,
        public ?array $authentication_methods = null,
        public ?array $authentication_attempts = null,
        public ?array $authentication_failure_reasons = null,
        public array $extra = [],
    ) {}
}
