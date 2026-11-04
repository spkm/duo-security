<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Requests\ListRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a user's groups: GET /admin/v1/users/{user_id}/groups. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListUserGroupsRequest extends ListRequest
{
    protected ?string $dto = Group::class;

    protected int $maxPageSize = 500;

    protected bool $cacheable = true;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     */
    public function __construct(protected string $userId, array $params = [])
    {
        parent::__construct($params);
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/groups';
    }
}
