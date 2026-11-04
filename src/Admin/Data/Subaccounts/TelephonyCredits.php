<?php

namespace spkm\DuoSecurity\Admin\Data\Subaccounts;

use spkm\DuoSecurity\Data\Data;

/**
 * A subaccount's telephony credits, or the credits a call added.
 */
readonly class TelephonyCredits extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?int $credits = null,
        public ?int $credits_added = null,
        public array $extra = [],
    ) {}
}
