<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Disassociate a hardware token from a user: DELETE /admin/v1/users/{user_id}/tokens/{token_id}. Requires the "Grant resource - Write" permission.
 */
class RemoveUserTokenRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, protected string $tokenId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/tokens/'.Path::segment($this->tokenId);
    }
}
