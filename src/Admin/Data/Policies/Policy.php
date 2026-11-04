<?php

namespace spkm\DuoSecurity\Admin\Data\Policies;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * A policy: where it applies and its sections, as Duo documents them (sections vary too much to type).
 */
readonly class Policy extends Data
{
    /**
     * @param  array<mixed>|null  $policy_applies_to
     * @param  array<mixed>|null  $sections
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $policy_key = null,
        public ?string $policy_name = null,
        public ?bool $is_global_policy = null,
        public ?CarbonImmutable $created_at = null,
        public ?CarbonImmutable $updated_at = null,
        public ?array $policy_applies_to = null,
        public ?array $sections = null,
        public array $extra = [],
    ) {}
}
