<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\AdminActivation;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create an activation link for a new administrator: POST /admin/v1/admins/activations. Requires the "Grant administrators - Write" permission.
 *
 * Parameters Duo documents: admin_name, admin_role, admin_role_id, send_email, valid_days.
 */
class CreateAdministratorActivationRequest extends WriteRequest
{
    protected ?string $dto = AdminActivation::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: admin_name, admin_role, admin_role_id, send_email, valid_days.
     */
    public function __construct(string $email, array $params = [])
    {
        $this->params = ['email' => $email, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/activations';
    }
}
