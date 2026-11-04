<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a pending administrator's activation link: DELETE /admin/v1/admins/{admin_id}/activation_link. Requires the "Grant administrators - Write" permission.
 */
class DeleteAdministratorActivationLinkRequest extends DeleteRequest
{
    protected ?string $dto = Admin::class;

    protected string $responseShape = self::ONE;

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
