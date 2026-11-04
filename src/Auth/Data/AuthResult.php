<?php

namespace spkm\DuoSecurity\Auth\Data;

use spkm\DuoSecurity\Data\Data;

/**
 * The outcome of an authentication (allow or deny, with the status that led there), or the txid of one started asynchronously.
 */
readonly class AuthResult extends Data
{
    protected const HIDDEN = ['trusted_device_token'];

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $result = null,
        public ?string $status = null,
        public ?string $status_msg = null,
        public ?string $trusted_device_token = null,
        public ?string $txid = null,
        public array $extra = [],
    ) {}
}
