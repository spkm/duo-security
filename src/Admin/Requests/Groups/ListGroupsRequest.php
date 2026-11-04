<?php

namespace spkm\DuoSecurity\Admin\Requests\Groups;

use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve groups: GET /admin/v1/groups. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset, group_id_list.
 */
class ListGroupsRequest extends ListRequest
{
    protected ?string $dto = Group::class;

    protected int $maxPageSize = 100;

    protected bool $cacheable = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/groups';
    }
}
