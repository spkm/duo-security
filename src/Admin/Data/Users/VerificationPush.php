<?php

namespace spkm\DuoSecurity\Admin\Data\Users;

use spkm\DuoSecurity\Data\Data;

/**
 * A verification push sent to a user: the code shown to them and the id to poll the answer with.
 */
readonly class VerificationPush extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $confirmation_code = null,
        public ?string $push_id = null,
        public array $extra = [],
    ) {}
}
