<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Clear an administrator's failed login attempts: POST /admin/v1/admins/{admin_id}/reset. Requires the "Grant administrators - Write" permission.
 */
class ResetAdministratorAuthAttemptsRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $adminId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/'.Path::segment($this->adminId).'/reset';
    }
}
