<?php

namespace spkm\DuoSecurity\Admin\Data\Phones;

use spkm\DuoSecurity\Data\Data;

/**
 * What was texted to a phone to install Duo Mobile.
 */
readonly class SmsInstallation extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $installation_msg = null,
        public array $extra = [],
    ) {}
}
