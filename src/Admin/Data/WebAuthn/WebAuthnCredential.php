<?php

namespace spkm\DuoSecurity\Admin\Data\WebAuthn;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Data\Data;

/**
 * A WebAuthn credential (a security key, Touch ID, Windows Hello...) of a user or an administrator.
 */
readonly class WebAuthnCredential extends Data
{
    /**
     * @param  array<mixed>|null  $transports
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $webauthnkey = null,
        public ?string $credential_id = null,
        public ?string $credential_name = null,
        public ?string $label = null,
        public ?string $aaguid = null,
        public ?string $registered_as = null,
        public ?array $transports = null,
        public ?CarbonImmutable $date_added = null,
        public ?CarbonImmutable $date_last_used = null,
        public ?bool $uv_capable = null,
        public ?bool $passwordless_authorized = null,
        public ?bool $backup_eligible = null,
        public ?string $backup_status = null,
        public ?User $user = null,
        public ?Admin $admin = null,
        public array $extra = [],
    ) {}
}
