<?php

namespace spkm\DuoSecurity\Admin\Data\Users;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\DesktopAuthenticator;
use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Admin\Data\WebAuthn\WebAuthnCredential;
use spkm\DuoSecurity\Data\Data;

/**
 * A user as the Admin API sends one, with their groups, phones, tokens and credentials.
 */
readonly class User extends Data
{
    protected const LISTS = ['groups' => Group::class, 'phones' => Phone::class, 'tokens' => Token::class, 'webauthncredentials' => WebAuthnCredential::class, 'desktop_authenticators' => DesktopAuthenticator::class];

    /**
     * @param  array<mixed>|null  $aliases
     * @param  array<mixed>|null  $custom_attributes
     * @param  Collection<int, Group>|null  $groups
     * @param  Collection<int, Phone>|null  $phones
     * @param  Collection<int, Token>|null  $tokens
     * @param  array<mixed>|null  $u2ftokens
     * @param  Collection<int, WebAuthnCredential>|null  $webauthncredentials
     * @param  Collection<int, DesktopAuthenticator>|null  $desktop_authenticators
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $user_id = null,
        public ?string $username = null,
        public ?string $alias1 = null,
        public ?string $alias2 = null,
        public ?string $alias3 = null,
        public ?string $alias4 = null,
        public ?array $aliases = null,
        public ?string $email = null,
        public ?string $firstname = null,
        public ?string $lastname = null,
        public ?string $realname = null,
        public ?string $notes = null,
        public ?string $status = null,
        public ?string $lockout_reason = null,
        public ?bool $enable_auto_prompt = null,
        public ?bool $is_enrolled = null,
        public ?bool $has_password = null,
        public ?string $external_id = null,
        public ?string $directory_key = null,
        public ?string $entra_federated_user_id = null,
        public ?array $custom_attributes = null,
        public ?string $date_of_birth = null,
        public ?CarbonImmutable $created = null,
        public ?CarbonImmutable $last_login = null,
        public ?CarbonImmutable $last_directory_sync = null,
        public ?CarbonImmutable $password_last_updated = null,
        public ?Collection $groups = null,
        public ?Collection $phones = null,
        public ?Collection $tokens = null,
        public ?array $u2ftokens = null,
        public ?Collection $webauthncredentials = null,
        public ?Collection $desktop_authenticators = null,
        public array $extra = [],
    ) {}
}
