<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\AllowedAuthMethods;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the factors administrators may log in to the Admin Panel with: GET /admin/v1/admins/allowed_auth_methods. Requires the "Grant administrators - Read" permission.
 */
class GetAllowedAdministratorAuthMethodsRequest extends GetRequest
{
    protected ?string $dto = AllowedAuthMethods::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/allowed_auth_methods';
    }
}
