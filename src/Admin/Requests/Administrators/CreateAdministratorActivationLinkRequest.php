<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Create an activation link for a pending administrator: POST /admin/v1/admins/{admin_id}/activation_link. Requires the "Grant administrators - Write" permission.
 */
class CreateAdministratorActivationLinkRequest extends WriteRequest
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
        return '/admin/v1/admins/'.Path::segment($this->adminId).'/activation_link';
    }
}
