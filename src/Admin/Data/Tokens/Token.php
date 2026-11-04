<?php

namespace spkm\DuoSecurity\Admin\Data\Tokens;

use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Data\Data;

/**
 * A hardware token (h6, h8, yk or d1), with the users and administrators it belongs to.
 */
readonly class Token extends Data
{
    protected const LISTS = ['users' => User::class, 'admins' => Admin::class];

    /**
     * @param  Collection<int, User>|null  $users
     * @param  Collection<int, Admin>|null  $admins
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $token_id = null,
        public ?string $serial = null,
        public ?string $type = null,
        public ?int $totp_step = null,
        public ?Collection $users = null,
        public ?Collection $admins = null,
        public array $extra = [],
    ) {}
}
