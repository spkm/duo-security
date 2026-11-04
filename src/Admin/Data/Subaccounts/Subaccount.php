<?php

namespace spkm\DuoSecurity\Admin\Data\Subaccounts;

use spkm\DuoSecurity\Data\Data;

/**
 * A subaccount of an MSP account, with the API hostname its own calls go to.
 */
readonly class Subaccount extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $account_id = null,
        public ?string $name = null,
        public ?string $api_hostname = null,
        public array $extra = [],
    ) {}
}
