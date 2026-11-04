<?php

namespace spkm\DuoSecurity\Admin\Requests\AdministrativeUnits;

use spkm\DuoSecurity\Admin\Data\AdministrativeUnits\AdministrativeUnit;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Add an administrator to an administrative unit: POST /admin/v1/administrative_units/{admin_unit_id}/admin/{admin_id}. Requires the "Grant administrators - Write" permission.
 */
class AddAdministrativeUnitAdminRequest extends WriteRequest
{
    protected ?string $dto = AdministrativeUnit::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $adminUnitId, protected string $adminId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/administrative_units/'.Path::segment($this->adminUnitId).'/admin/'.Path::segment($this->adminId);
    }
}
