<?php

namespace spkm\DuoSecurity\Admin\Data\Policies;

use Illuminate\Support\Collection;
use spkm\DuoSecurity\Data\Data;

/**
 * The policies a copy or a bulk update produced, with any warnings.
 */
readonly class PolicyBatch extends Data
{
    protected const LISTS = ['policies' => Policy::class];

    /**
     * @param  Collection<int, Policy>|null  $policies
     * @param  array<mixed>|null  $warnings
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?Collection $policies = null,
        public ?array $warnings = null,
        public array $extra = [],
    ) {}
}
