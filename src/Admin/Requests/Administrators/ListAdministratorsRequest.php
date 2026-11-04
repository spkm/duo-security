<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve administrators: GET /admin/v1/admins. Requires the "Grant administrators - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListAdministratorsRequest extends ListRequest
{
    protected ?string $dto = Admin::class;

    protected int $maxPageSize = 500;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins';
    }
}
