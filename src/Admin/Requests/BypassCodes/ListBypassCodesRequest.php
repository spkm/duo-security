<?php

namespace spkm\DuoSecurity\Admin\Requests\BypassCodes;

use spkm\DuoSecurity\Admin\Data\BypassCodes\BypassCode;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve every bypass code's metadata (not the codes): GET /admin/v1/bypass_codes. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListBypassCodesRequest extends ListRequest
{
    protected ?string $dto = BypassCode::class;

    protected int $maxPageSize = 500;

    protected bool $cacheable = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/bypass_codes';
    }
}
