<?php

namespace spkm\DuoSecurity\Admin\Data\Subaccounts;

use spkm\DuoSecurity\Data\Data;

/**
 * A subaccount's edition: PERSONAL (Duo Free), ENTERPRISE (Essentials), PLATFORM (Advantage) or BEYOND (Premier).
 */
readonly class Edition extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $edition = null,
        public array $extra = [],
    ) {}
}
