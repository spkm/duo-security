<?php

namespace spkm\DuoSecurity\Admin\Data\Passport;

use spkm\DuoSecurity\Data\Data;

/**
 * The Duo Passport configuration: whether it is on, for which groups, and the browsers it supports.
 */
readonly class PassportConfiguration extends Data
{
    /**
     * @param  array<mixed>|null  $enabled_groups
     * @param  array<mixed>|null  $disabled_groups
     * @param  array<mixed>|null  $custom_supported_browsers
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $enabled_status = null,
        public ?array $enabled_groups = null,
        public ?array $disabled_groups = null,
        public ?array $custom_supported_browsers = null,
        public array $extra = [],
    ) {}
}
