<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use spkm\DuoSecurity\Admin\Data\Policies\Policy;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve policies with their sections: GET /admin/v2/policies. Requires the "Grant resource - Read" permission.
 *
 * Needs v5 signing, which this package uses.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListPoliciesRequest extends ListRequest
{
    protected ?string $dto = Policy::class;

    protected int $maxPageSize = 100;

    public function resolveEndpoint(): string
    {
        return '/admin/v2/policies';
    }
}
