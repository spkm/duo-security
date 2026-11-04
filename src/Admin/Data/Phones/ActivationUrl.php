<?php

namespace spkm\DuoSecurity\Admin\Data\Phones;

use spkm\DuoSecurity\Data\Data;

/**
 * A Duo Mobile activation code for a phone, as a URL and a barcode image.
 */
readonly class ActivationUrl extends Data
{
    protected const HIDDEN = ['activation_url', 'activation_barcode', 'installation_url'];

    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $activation_url = null,
        public ?string $activation_barcode = null,
        public ?string $installation_url = null,
        public ?int $valid_secs = null,
        public array $extra = [],
    ) {}
}
