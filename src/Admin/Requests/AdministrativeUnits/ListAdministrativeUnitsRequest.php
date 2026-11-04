<?php

namespace spkm\DuoSecurity\Admin\Requests\AdministrativeUnits;

use spkm\DuoSecurity\Admin\Data\AdministrativeUnits\AdministrativeUnit;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve administrative units, or those of an administrator, a group or an integration: GET /admin/v1/administrative_units. Requires the "Grant administrators - Read" permission.
 *
 * Parameters Duo documents: limit, offset, admin_id, group_id, integration_key.
 */
class ListAdministrativeUnitsRequest extends ListRequest
{
    protected ?string $dto = AdministrativeUnit::class;

    protected int $maxPageSize = 500;

    protected bool $cacheable = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/administrative_units';
    }
}
