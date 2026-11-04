<?php

namespace spkm\DuoSecurity\Admin\Resources;

use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\AdminRoles\AdminRole;
use spkm\DuoSecurity\Admin\Requests\AdminRoles\GetAdminRoleRequest;
use spkm\DuoSecurity\Admin\Requests\AdminRoles\ListAdminRolesRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Admin Roles endpoints of the Admin API, from DuoSecurity::adminRoles().
 */
class AdminRolesResource extends BaseResource
{
    /**
     * Retrieve the standard and custom administrator roles: GET /admin/v1/admin_roles.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     * @return Collection<int, AdminRole>
     */
    public function listAdminRoles(array $params = []): Collection
    {
        return $this->fetch(new ListAdminRolesRequest($params));
    }

    /**
     * Retrieve an administrator role, with its administrators and directory sync groups: GET /admin/v1/admin_roles/{role_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getAdminRole(string $roleId, array $params = []): AdminRole
    {
        return $this->fetch(new GetAdminRoleRequest($roleId, $params));
    }
}
