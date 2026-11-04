<?php

namespace spkm\DuoSecurity\Admin\Data\Info;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * The counts of users with authentication attempts in a period, by result.
 */
readonly class UserAuthenticationAttempts extends Data
{
    /**
     * @param  array<mixed>|null  $user_authentication_attempts
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?CarbonImmutable $mintime = null,
        public ?CarbonImmutable $maxtime = null,
        public ?array $user_authentication_attempts = null,
        public array $extra = [],
    ) {}
}
