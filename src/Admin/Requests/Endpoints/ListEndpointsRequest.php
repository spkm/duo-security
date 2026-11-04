<?php

namespace spkm\DuoSecurity\Admin\Requests\Endpoints;

use spkm\DuoSecurity\Admin\Data\Endpoints\Endpoint;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve endpoints (Duo Premier and Advantage): GET /admin/v1/endpoints. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListEndpointsRequest extends ListRequest
{
    protected ?string $dto = Endpoint::class;

    protected int $maxPageSize = 500;

    protected bool $cacheable = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/endpoints';
    }
}
