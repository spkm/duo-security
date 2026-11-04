<?php

namespace spkm\DuoSecurity\Admin\Requests\Branding;

use spkm\DuoSecurity\Admin\Data\Branding\Branding;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the draft custom branding: GET /admin/v1/branding/draft. Requires the "Grant settings" permission.
 */
class GetDraftBrandingRequest extends GetRequest
{
    protected ?string $dto = Branding::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
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
