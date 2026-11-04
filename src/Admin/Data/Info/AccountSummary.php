<?php

namespace spkm\DuoSecurity\Admin\Data\Info;

use spkm\DuoSecurity\Data\Data;

/**
 * The account's id, edition and counts of users, administrators, integrations and the rest.
 */
readonly class AccountSummary extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $account_id = null,
        public ?string $edition = null,
        public ?int $admin_count = null,
        public ?int $integration_count = null,
        public ?int $group_count = null,
        public ?int $phone_count = null,
        public ?int $token_count = null,
        public ?int $user_count = null,
        public ?int $user_pending_deletion_count = null,
        public ?int $telephony_credits_remaining = null,
        public array $extra = [],
    ) {}
}
