<?php

namespace spkm\DuoSecurity\Admin\Data\Administrators;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * An activation link emailed to a pending administrator.
 */
readonly class ActivationLink extends Data
{
    protected const HIDDEN = ['code'];

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $admin_activation_id = null,
        public ?string $code = null,
        public ?string $email = null,
        public ?CarbonImmutable $expires = null,
        public array $extra = [],
    ) {}
}
