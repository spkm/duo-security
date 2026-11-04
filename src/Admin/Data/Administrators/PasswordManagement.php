<?php

namespace spkm\DuoSecurity\Admin\Data\Administrators;

use spkm\DuoSecurity\Data\Data;

/**
 * Whether an administrator's password is managed outside Duo.
 */
readonly class PasswordManagement extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $admin_id = null,
        public ?string $email = null,
        public ?bool $has_external_password_mgmt = null,
        public ?bool $password_changed = null,
        public array $extra = [],
    ) {}
}
