<?php

namespace spkm\DuoSecurity\Admin\Requests\AdministrativeUnits;

use spkm\DuoSecurity\Admin\Data\AdministrativeUnits\AdministrativeUnit;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Modify an administrative unit (its lists are added to): POST /admin/v1/administrative_units/{admin_unit_id}. Requires the "Grant administrators - Write" permission.
 *
 * Parameters Duo documents: name, description, restrict_by_groups, restrict_by_integrations, admins, groups, integrations.
 */
class ModifyAdministrativeUnitRequest extends WriteRequest
{
    protected ?string $dto = AdministrativeUnit::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: name, description, restrict_by_groups, restrict_by_integrations, admins, groups, integrations.
     */
    public function __construct(protected string $adminUnitId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/administrative_units/'.Path::segment($this->adminUnitId);
    }
}
