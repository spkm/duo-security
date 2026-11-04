<?php

namespace spkm\DuoSecurity\Admin\Resources\Concerns;

use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Admin\Requests\Users\GetUserRequest;

/**
 * Finders for users, beside the endpoints' own methods: by id (null when there is none) and by username or email,
 * which Duo matches exactly, one user at most.
 */
trait FindsUsers
{
    /**
     * The user with this id, or null when Duo has none.
     */
    public function find(string $userId): ?User
    {
        return $this->fetchOrNull(new GetUserRequest($userId));
    }

    /**
     * The user with this username (or alias), or null when Duo has none.
     */
    public function findByUsername(string $username): ?User
    {
        return $this->listUsers(['username' => $username])->first();
    }

    /**
     * The user with this email address, or null when Duo has none.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->listUsers(['email' => $email])->first();
    }
}
