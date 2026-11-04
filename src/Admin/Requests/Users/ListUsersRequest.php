<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve users, or the one with a username or email: GET /admin/v1/users. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset, username, email, user_id_list, username_list.
 */
class ListUsersRequest extends ListRequest
{
    protected ?string $dto = User::class;

    protected int $maxPageSize = 300;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users';
    }
}
