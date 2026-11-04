<?php

namespace spkm\DuoSecurity\Admin\Requests\AdministrativeUnits;

use spkm\DuoSecurity\Admin\Data\AdministrativeUnits\AdministrativeUnit;
use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Remove an integration from an administrative unit: DELETE /admin/v1/administrative_units/{admin_unit_id}/integration/{integration_key}. Requires the "Grant administrators - Write" permission.
 */
class RemoveAdministrativeUnitIntegrationRequest extends DeleteRequest
{
    protected ?string $dto = AdministrativeUnit::class;

    protected string $responseShape = self::ONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $adminUnitId, protected string $integrationKey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/administrative_units/'.Path::segment($this->adminUnitId).'/integration/'.Path::segment($this->integrationKey);
    }
}
