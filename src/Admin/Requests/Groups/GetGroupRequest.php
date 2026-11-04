<?php

namespace spkm\DuoSecurity\Admin\Requests\Groups;

use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a group (without its members): GET /admin/v2/groups/{group_id}. Requires the "Grant resource - Read" permission.
 */
class GetGroupRequest extends GetRequest
{
    protected ?string $dto = Group::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $groupId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/groups/'.Path::segment($this->groupId);
    }
}
