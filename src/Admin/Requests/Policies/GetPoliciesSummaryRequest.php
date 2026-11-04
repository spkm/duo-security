<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use spkm\DuoSecurity\Admin\Data\Policies\PolicySummary;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve every policy's name, key and where it applies: GET /admin/v2/policies/summary. Requires the "Grant resource - Read" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class GetPoliciesSummaryRequest extends GetRequest
{
    protected ?string $dto = PolicySummary::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/policies/summary';
    }
}
