<?php

namespace spkm\DuoSecurity\Admin\Data\Info;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * The counts of authentication attempts in a period, by result.
 */
readonly class AuthenticationAttempts extends Data
{
    /**
     * @param  array<mixed>|null  $authentication_attempts
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?CarbonImmutable $mintime = null,
        public ?CarbonImmutable $maxtime = null,
        public ?array $authentication_attempts = null,
        public array $extra = [],
    ) {}
}
