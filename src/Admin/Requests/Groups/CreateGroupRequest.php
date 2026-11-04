<?php

namespace spkm\DuoSecurity\Admin\Requests\Groups;

use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create a group: POST /admin/v1/groups. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: desc, status.
 */
class CreateGroupRequest extends WriteRequest
{
    protected ?string $dto = Group::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: desc, status.
     */
    public function __construct(string $name, array $params = [])
    {
        $this->params = ['name' => $name, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/groups';
    }
}
