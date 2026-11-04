<?php

namespace spkm\DuoSecurity\Auth\Data;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use spkm\DuoSecurity\Data\Data;

/**
 * Whether a user may authenticate (auth, allow, deny or enroll), the devices they can use, and the enrollment portal or Verified Push details when they apply.
 */
readonly class PreauthResult extends Data
{
    protected const LISTS = ['devices' => Device::class];

    protected const HIDDEN = ['verification_code'];

    /**
     * @param  Collection<int, Device>|null  $devices
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $result = null,
        public ?string $status_msg = null,
        public ?Collection $devices = null,
        public ?string $enroll_portal_url = null,
        public ?string $txid = null,
        public ?string $verification_code = null,
        public ?CarbonImmutable $expiration = null,
        public array $extra = [],
    ) {}
}
