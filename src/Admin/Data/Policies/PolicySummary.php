<?php

namespace spkm\DuoSecurity\Admin\Data\Policies;

use spkm\DuoSecurity\Data\Data;

/**
 * Every policy's name, key and where it applies.
 */
readonly class PolicySummary extends Data
{
    /**
     * @param  array<mixed>|null  $policies
     * @param  array<mixed>|null  $warnings
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?array $policies = null,
        public ?int $policy_count = null,
        public ?bool $response_is_truncated = null,
        public ?array $warnings = null,
        public array $extra = [],
    ) {}
}
