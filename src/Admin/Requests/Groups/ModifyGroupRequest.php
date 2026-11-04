<?php

namespace spkm\DuoSecurity\Admin\Requests\Groups;

use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Modify a group: POST /admin/v1/groups/{group_id}. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: name, desc, status.
 */
class ModifyGroupRequest extends WriteRequest
{
    protected ?string $dto = Group::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: name, desc, status.
     */
    public function __construct(protected string $groupId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/groups/'.Path::segment($this->groupId);
    }
}
