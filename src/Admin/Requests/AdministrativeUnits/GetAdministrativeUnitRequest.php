<?php

namespace spkm\DuoSecurity\Admin\Requests\AdministrativeUnits;

use spkm\DuoSecurity\Admin\Data\AdministrativeUnits\AdministrativeUnit;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve an administrative unit: GET /admin/v1/administrative_units/{admin_unit_id}. Requires the "Grant administrators - Read" permission.
 */
class GetAdministrativeUnitRequest extends GetRequest
{
    protected ?string $dto = AdministrativeUnit::class;

    protected bool $cacheable = true;

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
