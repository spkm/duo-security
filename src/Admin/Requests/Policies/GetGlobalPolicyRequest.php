<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use spkm\DuoSecurity\Admin\Data\Policies\Policy;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the global policy: GET /admin/v2/policies/global. Requires the "Grant resource - Read" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class GetGlobalPolicyRequest extends GetRequest
{
    protected ?string $dto = Policy::class;

    protected bool $cacheable = true;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/policies/global';
    }
}
