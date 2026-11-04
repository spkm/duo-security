<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Modify a user: POST /admin/v1/users/{user_id}. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: username, alias1, alias2, alias3, alias4, aliases, custom_attributes, date_of_birth, email, enable_auto_prompt, firstname, lastname, notes, realname, status.
 */
class ModifyUserRequest extends WriteRequest
{
    protected ?string $dto = User::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: username, alias1, alias2, alias3, alias4, aliases, custom_attributes, date_of_birth, email, enable_auto_prompt, firstname, lastname, notes, realname, status.
     */
    public function __construct(protected string $userId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId);
    }
}
