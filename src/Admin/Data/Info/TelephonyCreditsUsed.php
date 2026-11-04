<?php

namespace spkm\DuoSecurity\Admin\Data\Info;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * The telephony credits used in a period.
 */
readonly class TelephonyCreditsUsed extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?CarbonImmutable $mintime = null,
        public ?CarbonImmutable $maxtime = null,
        public ?int $telephony_credits_used = null,
        public array $extra = [],
    ) {}
}
