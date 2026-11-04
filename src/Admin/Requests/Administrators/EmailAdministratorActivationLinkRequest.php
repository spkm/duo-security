<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\ActivationLink;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Email a pending administrator their activation link: POST /admin/v1/admins/{admin_id}/activation_link/email. Requires the "Grant administrators - Write" permission.
 */
class EmailAdministratorActivationLinkRequest extends WriteRequest
{
    protected ?string $dto = ActivationLink::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $adminId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/'.Path::segment($this->adminId).'/activation_link/email';
    }
}
