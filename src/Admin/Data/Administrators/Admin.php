<?php

namespace spkm\DuoSecurity\Admin\Data\Administrators;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Admin\Data\WebAuthn\WebAuthnCredential;
use spkm\DuoSecurity\Data\Data;

/**
 * An administrator, with their role, phone and credentials.
 */
readonly class Admin extends Data
{
    protected const LISTS = ['webauthncredentials' => WebAuthnCredential::class];

    protected const HIDDEN = ['activation_url'];

    /**
     * @param  Collection<int, WebAuthnCredential>|null  $webauthncredentials
     * @param  array<mixed>|null  $admin_units
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $admin_id = null,
        public ?string $email = null,
        public ?string $name = null,
        public ?string $phone = null,
        public ?Phone $phone_details = null,
        public ?Token $hardtoken = null,
        public ?Collection $webauthncredentials = null,
        public ?string $role = null,
        public ?string $role_id = null,
        public ?string $subaccount_role = null,
        public ?string $subaccount_role_id = null,
        public ?string $status = null,
        public ?bool $restricted_by_admin_units = null,
        public ?array $admin_units = null,
        public ?bool $password_change_required = null,
        public ?CarbonImmutable $last_login = null,
        public ?CarbonImmutable $created = null,
        public ?CarbonImmutable $last_directory_sync = null,
        public ?string $activation_url = null,
        public ?CarbonImmutable $activation_url_expires = null,
        public array $extra = [],
    ) {}
}
