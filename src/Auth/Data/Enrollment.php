<?php

namespace spkm\DuoSecurity\Auth\Data;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * A new user and their Duo Mobile activation code, as a QR code image and a link.
 */
readonly class Enrollment extends Data
{
    protected const HIDDEN = ['activation_code', 'activation_url', 'activation_barcode'];

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $user_id = null,
        public ?string $username = null,
        public ?string $activation_barcode = null,
        public ?string $activation_code = null,
        public ?string $activation_url = null,
        public ?CarbonImmutable $expiration = null,
        public array $extra = [],
    ) {}
}
