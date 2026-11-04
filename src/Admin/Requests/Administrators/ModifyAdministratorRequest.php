<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Modify an administrator: POST /admin/v1/admins/{admin_id}. Requires the "Grant administrators - Write" permission.
 *
 * Parameters Duo documents: name, phone, password_change_required, role, role_id, restricted_by_admin_units, status, subaccount_role, subaccount_role_id, token_id.
 */
class ModifyAdministratorRequest extends WriteRequest
{
    protected ?string $dto = Admin::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: name, phone, password_change_required, role, role_id, restricted_by_admin_units, status, subaccount_role, subaccount_role_id, token_id.
     */
    public function __construct(protected string $adminId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/'.Path::segment($this->adminId);
    }
}
