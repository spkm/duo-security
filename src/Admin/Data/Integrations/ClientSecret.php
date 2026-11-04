<?php

namespace spkm\DuoSecurity\Admin\Data\Integrations;

use spkm\DuoSecurity\Data\Data;

/**
 * An OAuth or OIDC application's client secret.
 */
readonly class ClientSecret extends Data
{
    protected const HIDDEN = ['client_secret'];

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $client_secret = null,
        public array $extra = [],
    ) {}
}
