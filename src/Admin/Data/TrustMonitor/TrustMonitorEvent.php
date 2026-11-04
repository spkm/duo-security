<?php

namespace spkm\DuoSecurity\Admin\Data\TrustMonitor;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * An event Trust Monitor surfaced, why, and how it was triaged; the authentication it concerns is in surfaced_auth.
 */
readonly class TrustMonitorEvent extends Data
{
    /**
     * @param  array<mixed>|null  $priority_reasons
     * @param  array<mixed>|null  $explanations
     * @param  array<mixed>|null  $enabled_by
     * @param  array<mixed>|null  $enabled_for
     * @param  array<mixed>|null  $surfaced_auth
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $sekey = null,
        public ?string $type = null,
        public ?string $state = null,
        public ?CarbonImmutable $state_updated_timestamp = null,
        public ?CarbonImmutable $surfaced_timestamp = null,
        public ?bool $priority_event = null,
        public ?array $priority_reasons = null,
        public ?array $explanations = null,
        public ?string $triage_event_uri = null,
        public ?bool $triaged_as_interesting = null,
        public ?bool $bypass_status_enabled = null,
        public ?array $enabled_by = null,
        public ?array $enabled_for = null,
        public ?bool $from_common_netblock = null,
        public ?bool $from_new_user = null,
        public ?bool $low_risk_ip = null,
        public ?array $surfaced_auth = null,
        public array $extra = [],
    ) {}
}
