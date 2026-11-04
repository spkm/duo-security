<?php

namespace spkm\DuoSecurity\Admin\Data\BypassCodes;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Data\Data;

/**
 * A bypass code's metadata (never the code itself) and the user it belongs to.
 */
readonly class BypassCode extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $bypass_code_id = null,
        public ?string $admin_email = null,
        public ?CarbonImmutable $created = null,
        public ?CarbonImmutable $expiration = null,
        public ?int $reuse_count = null,
        public ?bool $endpoint_verification = null,
        public ?User $user = null,
        public array $extra = [],
    ) {}
}
