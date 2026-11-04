<?php

namespace spkm\DuoSecurity\Admin\Data\Users;

use spkm\DuoSecurity\Data\Data;

/**
 * The answer to a verification push: approve, deny, fraud or waiting.
 */
readonly class VerificationPushResponse extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $push_id = null,
        public ?string $result = null,
        public array $extra = [],
    ) {}
}
