<?php

namespace spkm\DuoSecurity\Admin\Data\Policies;

use spkm\DuoSecurity\Data\Data;

/**
 * The policy in effect for a user and an application: its sections and the policies they came from.
 */
readonly class PolicyCalculation extends Data
{
    /**
     * @param  array<mixed>|null  $sections
     * @param  array<mixed>|null  $source_policies
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?array $sections = null,
        public ?array $source_policies = null,
        public array $extra = [],
    ) {}
}
