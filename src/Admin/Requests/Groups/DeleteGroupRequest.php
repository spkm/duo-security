<?php

namespace spkm\DuoSecurity\Admin\Requests\Groups;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a group: DELETE /admin/v1/groups/{group_id}. Requires the "Grant resource - Write" permission.
 */
class DeleteGroupRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
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
