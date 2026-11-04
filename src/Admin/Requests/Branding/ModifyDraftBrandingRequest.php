<?php

namespace spkm\DuoSecurity\Admin\Requests\Branding;

use spkm\DuoSecurity\Admin\Data\Branding\Branding;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Modify the draft custom branding: POST /admin/v1/branding/draft. Requires the "Grant settings" permission.
 *
 * Parameters Duo documents: background_img, card_accent_color, logo, page_background_color, powered_by_duo, sso_custom_username_label, user_ids.
 */
class ModifyDraftBrandingRequest extends WriteRequest
{
    protected ?string $dto = Branding::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: background_img, card_accent_color, logo, page_background_color, powered_by_duo, sso_custom_username_label, user_ids.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/branding/draft';
    }
}
