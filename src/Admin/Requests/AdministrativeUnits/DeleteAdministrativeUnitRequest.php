<?php

namespace spkm\DuoSecurity\Admin\Requests\AdministrativeUnits;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete an administrative unit: DELETE /admin/v1/administrative_units/{admin_unit_id}. Requires the "Grant administrators - Write" permission.
 */
class DeleteAdministrativeUnitRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
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
