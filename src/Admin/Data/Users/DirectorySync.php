<?php

namespace spkm\DuoSecurity\Admin\Data\Users;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * A user directory sync and when it last ran.
 */
readonly class DirectorySync extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $directory_key = null,
        public ?string $directory_type = null,
        public ?string $name = null,
        public ?bool $paused = null,
        public ?string $last_full_sync_result = null,
        public ?CarbonImmutable $last_full_sync_time = null,
        public array $extra = [],
    ) {}
}
