<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Admin\Data\Groups\GroupMember;
use spkm\DuoSecurity\Admin\Requests\Groups\CreateGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\DeleteGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\GetGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\ListGroupMembersRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\ListGroupsRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\ModifyGroupRequest;
use spkm\DuoSecurity\Admin\Resources\Concerns\FindsGroups;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Groups endpoints of the Admin API, from DuoSecurity::groups(). Beside the endpoints' own methods, find() gives a record by id, or null.
 */
class GroupsResource extends BaseResource
{
    use FindsGroups;

    /**
     * Retrieve groups: GET /admin/v1/groups.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset, group_id_list.
     * @return ListQuery<Group>
     */
    public function listGroups(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListGroupsRequest([...$params, ...$page]), Group::class);
    }

    /**
     * Create a group: POST /admin/v1/groups.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: desc, status.
     */
    public function createGroup(string $name, array $params = []): Group
    {
        return $this->fetch(new CreateGroupRequest($name, $params));
    }

    /**
     * Retrieve a group (without its members): GET /admin/v2/groups/{group_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getGroup(string $groupId, array $params = []): Group
    {
        return $this->fetch(new GetGroupRequest($groupId, $params));
    }

    /**
     * Retrieve a group's members: GET /admin/v2/groups/{group_id}/users.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<GroupMember>
     */
    public function listGroupMembers(string $groupId, array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListGroupMembersRequest($groupId, [...$params, ...$page]), GroupMember::class);
    }

    /**
     * Modify a group: POST /admin/v1/groups/{group_id}.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: name, desc, status.
     */
    public function modifyGroup(string $groupId, array $params = []): Group
    {
        return $this->fetch(new ModifyGroupRequest($groupId, $params));
    }

    /**
     * Delete a group: DELETE /admin/v1/groups/{group_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteGroup(string $groupId, array $params = []): bool
    {
        return $this->perform(new DeleteGroupRequest($groupId, $params));
    }
}
