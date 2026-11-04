<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\PasswordManagement;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve an administrator's external password management status: GET /admin/v1/admins/{admin_id}/password_mgmt. Requires the "Grant administrators - Read" permission.
 */
class GetAdministratorPasswordManagementRequest extends GetRequest
{
    protected ?string $dto = PasswordManagement::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
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
