<?php

namespace spkm\DuoSecurity\Admin\Data\Groups;

use spkm\DuoSecurity\Data\Data;

/**
 * A group of users; the *_enabled fields are legacy and no longer apply.
 */
readonly class Group extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $group_id = null,
        public ?string $name = null,
        public ?string $desc = null,
        public ?string $status = null,
        public ?bool $push_enabled = null,
        public ?bool $sms_enabled = null,
        public ?bool $voice_enabled = null,
        public ?bool $mobile_otp_enabled = null,
        public array $extra = [],
    ) {}
}
