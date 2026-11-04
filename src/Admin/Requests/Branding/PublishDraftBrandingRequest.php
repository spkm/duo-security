<?php

namespace spkm\DuoSecurity\Admin\Requests\Branding;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Publish the draft custom branding as the live one: POST /admin/v1/branding/draft/publish. Requires the "Grant settings" permission.
 */
class PublishDraftBrandingRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/branding/draft/publish';
    }
}
