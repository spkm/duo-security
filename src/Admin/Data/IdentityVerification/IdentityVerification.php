<?php

namespace spkm\DuoSecurity\Admin\Data\IdentityVerification;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * An identity verification: its status (created, started, expired, verified, failed, unknown, canceled) and, when just started, the access code to redirect the user with.
 */
readonly class IdentityVerification extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $inquiry_id = null,
        public ?string $status = null,
        public ?string $flow_type = null,
        public ?string $access_code = null,
        public ?CarbonImmutable $expires_at = null,
        public ?CarbonImmutable $updated_at = null,
        public array $extra = [],
    ) {}
}
