<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Add a user to a group: POST /admin/v1/users/{user_id}/groups. Requires the "Grant resource - Write" permission.
 */
class AddUserToGroupRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, string $groupId, array $params = [])
    {
        $this->params = ['group_id' => $groupId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/groups';
    }
}
