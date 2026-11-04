<?php

namespace spkm\DuoSecurity\Admin\Requests\Branding;

use spkm\DuoSecurity\Admin\Data\Branding\CustomMessaging;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Modify the custom messaging shown to users: POST /admin/v1/branding/custom_messaging. Requires the "Grant settings" permission.
 *
 * Parameters Duo documents: help_links, help_text, locale.
 */
class ModifyCustomMessagingRequest extends WriteRequest
{
    protected ?string $dto = CustomMessaging::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: help_links, help_text, locale.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/branding/custom_messaging';
    }
}
