<?php

namespace spkm\DuoSecurity\Admin\Data\Administrators;

use spkm\DuoSecurity\Data\Data;

/**
 * The outcome of synchronizing one administrator from a directory.
 */
readonly class AdminSyncResult extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $message = null,
        public ?Admin $admin = null,
        public array $extra = [],
    ) {}
}
