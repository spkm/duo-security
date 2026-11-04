<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create an administrator, pending activation: POST /admin/v1/admins. Requires the "Grant administrators - Write" permission.
 *
 * Parameters Duo documents: phone, role, role_id, restricted_by_admin_units, send_email, subaccount_role, subaccount_role_id, token_id, valid_days.
 */
class CreateAdministratorRequest extends WriteRequest
{
    protected ?string $dto = Admin::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: phone, role, role_id, restricted_by_admin_units, send_email, subaccount_role, subaccount_role_id, token_id, valid_days.
     */
    public function __construct(string $email, string $name, array $params = [])
    {
        $this->params = ['email' => $email, 'name' => $name, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins';
    }
}
