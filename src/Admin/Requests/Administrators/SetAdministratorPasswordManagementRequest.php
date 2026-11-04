<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\PasswordManagement;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Set an administrator's external password management, and their password: POST /admin/v1/admins/{admin_id}/password_mgmt. Requires the "Grant administrators - Write" permission.
 *
 * Parameters Duo documents: has_external_password_mgmt, password.
 */
class SetAdministratorPasswordManagementRequest extends WriteRequest
{
    protected ?string $dto = PasswordManagement::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: has_external_password_mgmt, password.
     */
    public function __construct(protected string $adminId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/'.Path::segment($this->adminId).'/password_mgmt';
    }
}
