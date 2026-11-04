<?php

namespace spkm\DuoSecurity\Admin\Data\Administrators;

use spkm\DuoSecurity\Data\Data;

/**
 * The factors administrators may log in to the Admin Panel with.
 */
readonly class AllowedAuthMethods extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?bool $hardware_token_enabled = null,
        public ?bool $mobile_otp_enabled = null,
        public ?bool $push_enabled = null,
        public ?bool $sms_enabled = null,
        public ?bool $verified_push_enabled = null,
        public ?int $verified_push_length = null,
        public ?bool $voice_enabled = null,
        public ?bool $webauthn_enabled = null,
        public ?bool $yubikey_enabled = null,
        public array $extra = [],
    ) {}
}
