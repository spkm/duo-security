<?php

namespace spkm\DuoSecurity\Admin\Data\Branding;

use spkm\DuoSecurity\Data\Data;

/**
 * The custom branding of the Universal Prompt, live or draft (the images as base64).
 */
readonly class Branding extends Data
{
    /**
     * @param  array<mixed>|null  $draft_users
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $background_image = null,
        public ?string $card_accent_color = null,
        public ?string $logo = null,
        public ?string $page_background_color = null,
        public ?bool $powered_by_duo = null,
        public ?string $sso_custom_username_label = null,
        public ?array $draft_users = null,
        public array $extra = [],
    ) {}
}
