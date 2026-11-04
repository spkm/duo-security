<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * A call or text Duo made, and the credits it cost.
 */
readonly class TelephonyLogEvent extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $telephony_id = null,
        public ?string $txid = null,
        public ?CarbonImmutable $ts = null,
        public ?string $context = null,
        public ?string $type = null,
        public ?string $phone = null,
        public ?int $credits = null,
        public array $extra = [],
    ) {}
}
