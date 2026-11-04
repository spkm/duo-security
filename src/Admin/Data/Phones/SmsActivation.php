<?php

namespace spkm\DuoSecurity\Admin\Data\Phones;

use spkm\DuoSecurity\Data\Data;

/**
 * What was texted to a phone to activate Duo Mobile.
 */
readonly class SmsActivation extends Data
{
    protected const HIDDEN = ['activation_barcode'];

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $activation_msg = null,
        public ?string $activation_barcode = null,
        public ?string $installation_msg = null,
        public ?int $valid_secs = null,
        public array $extra = [],
    ) {}
}
