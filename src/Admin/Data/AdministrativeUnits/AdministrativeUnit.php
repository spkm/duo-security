<?php

namespace spkm\DuoSecurity\Admin\Data\AdministrativeUnits;

use spkm\DuoSecurity\Data\Data;

/**
 * An administrative unit: the administrators in it and the groups and integrations it restricts them to.
 */
readonly class AdministrativeUnit extends Data
{
    /**
     * @param  array<mixed>|null  $admins
     * @param  array<mixed>|null  $groups
     * @param  array<mixed>|null  $integrations
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $admin_unit_id = null,
        public ?string $name = null,
        public ?string $description = null,
        public ?bool $restrict_by_groups = null,
        public ?bool $restrict_by_integrations = null,
        public ?array $admins = null,
        public ?array $groups = null,
        public ?array $integrations = null,
        public array $extra = [],
    ) {}
}
