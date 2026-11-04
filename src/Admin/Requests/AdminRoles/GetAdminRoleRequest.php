<?php

namespace spkm\DuoSecurity\Admin\Requests\AdminRoles;

use spkm\DuoSecurity\Admin\Data\AdminRoles\AdminRole;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve an administrator role, with its administrators and directory sync groups: GET /admin/v1/admin_roles/{role_id}. Requires the "Grant administrators - Read" permission.
 */
class GetAdminRoleRequest extends GetRequest
{
    protected ?string $dto = AdminRole::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $roleId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admin_roles/'.Path::segment($this->roleId);
    }
}
