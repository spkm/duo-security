<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\AdminActivation;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve pending administrator activations: GET /admin/v1/admins/activations. Requires the "Grant administrators - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListAdministratorActivationsRequest extends ListRequest
{
    protected ?string $dto = AdminActivation::class;

    protected int $maxPageSize = 500;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/activations';
    }
}
