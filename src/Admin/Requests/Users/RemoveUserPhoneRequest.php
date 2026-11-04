<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Disassociate a phone from a user (the phone itself stays): DELETE /admin/v1/users/{user_id}/phones/{phone_id}. Requires the "Grant resource - Write" permission.
 */
class RemoveUserPhoneRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, protected string $phoneId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/phones/'.Path::segment($this->phoneId);
    }
}
