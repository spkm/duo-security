<?php

namespace spkm\DuoSecurity\Admin\Data\Integrations;

use spkm\DuoSecurity\Data\Data;

/**
 * An integration's secret key, in full.
 */
readonly class SecretKey extends Data
{
    protected const HIDDEN = ['skey'];

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $skey = null,
        public array $extra = [],
    ) {}
}
