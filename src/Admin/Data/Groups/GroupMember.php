<?php

namespace spkm\DuoSecurity\Admin\Data\Groups;

use spkm\DuoSecurity\Data\Data;

/**
 * A member of a group.
 */
readonly class GroupMember extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $user_id = null,
        public ?string $username = null,
        public array $extra = [],
    ) {}
}
