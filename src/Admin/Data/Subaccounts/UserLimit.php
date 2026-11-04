<?php

namespace spkm\DuoSecurity\Admin\Data\Subaccounts;

use spkm\DuoSecurity\Data\Data;

/**
 * A subaccount's user count and hard user limit (a number, or "Not set").
 */
readonly class UserLimit extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?int $current_users_count = null,
        public ?string $user_limit = null,
        public array $extra = [],
    ) {}
}
