<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\PasswordManagement;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve every administrator's external password management status: GET /admin/v1/admins/password_mgmt. Requires the "Grant administrators - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListAdministratorPasswordManagementRequest extends ListRequest
{
    protected ?string $dto = PasswordManagement::class;

    protected int $maxPageSize = 500;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/password_mgmt';
    }
}
