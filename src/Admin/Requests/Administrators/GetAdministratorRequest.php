<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve an administrator: GET /admin/v1/admins/{admin_id}. Requires the "Grant administrators - Read" permission.
 */
class GetAdministratorRequest extends GetRequest
{
    protected ?string $dto = Admin::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
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
