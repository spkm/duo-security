<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Associate a hardware token with a user: POST /admin/v1/users/{user_id}/tokens. Requires the "Grant resource - Write" permission.
 */
class AddUserTokenRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, string $tokenId, array $params = [])
    {
        $this->params = ['token_id' => $tokenId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/tokens';
    }
}
