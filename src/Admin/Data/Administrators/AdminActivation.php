<?php

namespace spkm\DuoSecurity\Admin\Data\Administrators;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * A pending administrator activation: the link, who it is for and when it expires.
 */
readonly class AdminActivation extends Data
{
    protected const HIDDEN = ['code', 'link'];

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $admin_activation_id = null,
        public ?string $admin_id = null,
        public ?string $admin_name = null,
        public ?string $admin_role = null,
        public ?string $admin_role_id = null,
        public ?string $code = null,
        public ?string $email = null,
        public ?bool $email_sent = null,
        public ?CarbonImmutable $expires = null,
        public ?string $link = null,
        public ?string $message = null,
        public ?string $subject = null,
        public ?int $valid_days = null,
        public array $extra = [],
    ) {}
}
