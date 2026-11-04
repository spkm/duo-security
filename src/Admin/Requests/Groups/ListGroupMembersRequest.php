<?php

namespace spkm\DuoSecurity\Admin\Requests\Groups;

use spkm\DuoSecurity\Admin\Data\Groups\GroupMember;
use spkm\DuoSecurity\Requests\ListRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a group's members: GET /admin/v2/groups/{group_id}/users. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListGroupMembersRequest extends ListRequest
{
    protected ?string $dto = GroupMember::class;

    protected int $maxPageSize = 500;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     */
    public function __construct(protected string $groupId, array $params = [])
    {
        parent::__construct($params);
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/groups/'.Path::segment($this->groupId).'/users';
    }
}
