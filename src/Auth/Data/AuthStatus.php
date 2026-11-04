<?php

namespace spkm\DuoSecurity\Auth\Data;

use spkm\DuoSecurity\Data\Data;

/**
 * The latest status of an asynchronous authentication: waiting, or allow or deny once it has ended.
 */
readonly class AuthStatus extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $result = null,
        public ?string $status = null,
        public ?string $status_msg = null,
        public ?string $trusted_device_token = null,
        public array $extra = [],
    ) {}
}
