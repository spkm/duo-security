<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Associate a phone with a user: POST /admin/v1/users/{user_id}/phones. Requires the "Grant resource - Write" permission.
 */
class AddUserPhoneRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, string $phoneId, array $params = [])
    {
        $this->params = ['phone_id' => $phoneId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/phones';
    }
}
