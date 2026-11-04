<?php

namespace spkm\DuoSecurity\Admin\Data\Phones;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Data\Data;

/**
 * A phone, with the users it belongs to.
 */
readonly class Phone extends Data
{
    protected const LISTS = ['users' => User::class];

    /**
     * @param  array<mixed>|null  $capabilities
     * @param  Collection<int, User>|null  $users
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $phone_id = null,
        public ?string $number = null,
        public ?string $name = null,
        public ?string $extension = null,
        public ?string $type = null,
        public ?string $platform = null,
        public ?string $model = null,
        public ?string $os_version = null,
        public ?string $app_version = null,
        public ?bool $activated = null,
        public ?array $capabilities = null,
        public ?CarbonImmutable $last_seen = null,
        public ?CarbonImmutable $last_activated_date = null,
        public ?CarbonImmutable $creation_date = null,
        public ?bool $sms_passcodes_sent = null,
        public ?string $encrypted = null,
        public ?string $fingerprint = null,
        public ?string $screenlock = null,
        public ?string $tampered = null,
        public ?bool $quarantined = null,
        public ?string $predelay = null,
        public ?string $postdelay = null,
        public ?Collection $users = null,
        public array $extra = [],
    ) {}
}
