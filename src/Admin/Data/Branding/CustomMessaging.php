<?php

namespace spkm\DuoSecurity\Admin\Data\Branding;

use spkm\DuoSecurity\Data\Data;

/**
 * The help text and links shown to users in the Universal Prompt.
 */
readonly class CustomMessaging extends Data
{
    /**
     * @param  array<mixed>|null  $help_links
     * @param  array<mixed>|null  $help_text_by_locale
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?array $help_links = null,
        public ?string $help_text = null,
        public ?array $help_text_by_locale = null,
        public ?string $locale = null,
        public array $extra = [],
    ) {}
}
