<?php

namespace spkm\DuoSecurity\Admin\Resources\Concerns;

use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Admin\Requests\Groups\GetGroupRequest;

/**
 * A finder for groups, beside the endpoints' own methods: by id, null when there is none.
 */
trait FindsGroups
{
    /**
     * The group with this id, or null when Duo has none.
     */
    public function find(string $groupId): ?Group
    {
        return $this->fetchOrNull(new GetGroupRequest($groupId));
    }
}
