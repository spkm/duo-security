<?php

namespace spkm\DuoSecurity\Admin\Requests\AdministrativeUnits;

use spkm\DuoSecurity\Admin\Data\AdministrativeUnits\AdministrativeUnit;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create an administrative unit: POST /admin/v1/administrative_units. Requires the "Grant administrators - Write" permission.
 *
 * Parameters Duo documents: restrict_by_groups, restrict_by_integrations, admins, groups, integrations.
 */
class CreateAdministrativeUnitRequest extends WriteRequest
{
    protected ?string $dto = AdministrativeUnit::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: restrict_by_groups, restrict_by_integrations, admins, groups, integrations.
     */
    public function __construct(string $name, string $description, array $params = [])
    {
        $this->params = ['name' => $name, 'description' => $description, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/administrative_units';
    }
}
