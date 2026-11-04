<?php

namespace spkm\DuoSecurity\Admin\Requests\AdminRoles;

use spkm\DuoSecurity\Admin\Data\AdminRoles\AdminRole;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the standard and custom administrator roles: GET /admin/v1/admin_roles. Requires the "Grant administrators - Read" permission.
 */
class ListAdminRolesRequest extends GetRequest
{
    protected ?string $dto = AdminRole::class;

    protected string $responseShape = self::LIST;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admin_roles';
    }
}
