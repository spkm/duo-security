<?php

namespace spkm\DuoSecurity\Admin\Data\AdminRoles;

use spkm\DuoSecurity\Data\Data;

/**
 * A standard or custom administrator role and, when fetched by id, its administrators and directory sync groups.
 */
readonly class AdminRole extends Data
{
    /**
     * @param  array<mixed>|null  $admins
     * @param  array<mixed>|null  $admin_sync_groups
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $role_id = null,
        public ?string $name = null,
        public ?string $description = null,
        public ?bool $in_use = null,
        public ?bool $is_custom = null,
        public ?array $admins = null,
        public ?array $admin_sync_groups = null,
        public array $extra = [],
    ) {}
}
