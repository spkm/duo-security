<?php

namespace spkm\DuoSecurity\Admin\Requests\AdministrativeUnits;

use spkm\DuoSecurity\Admin\Data\AdministrativeUnits\AdministrativeUnit;
use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Remove an administrator from an administrative unit: DELETE /admin/v1/administrative_units/{admin_unit_id}/admin/{admin_id}. Requires the "Grant administrators - Write" permission.
 */
class RemoveAdministrativeUnitAdminRequest extends DeleteRequest
{
    protected ?string $dto = AdministrativeUnit::class;

    protected string $responseShape = self::ONE;

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
