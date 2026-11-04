<?php

namespace spkm\DuoSecurity\Admin\Data\Users;

use spkm\DuoSecurity\Data\Data;

/**
 * The outcome of synchronizing one user from a directory.
 */
readonly class DirectorySyncResult extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $message = null,
        public ?User $user = null,
        public array $extra = [],
    ) {}
}
