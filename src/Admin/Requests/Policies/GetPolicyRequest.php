<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use spkm\DuoSecurity\Admin\Data\Policies\Policy;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a policy: GET /admin/v2/policies/{policy_key}. Requires the "Grant resource - Read" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class GetPolicyRequest extends GetRequest
{
    protected ?string $dto = Policy::class;

    protected bool $cacheable = true;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $policyKey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/policies/'.Path::segment($this->policyKey);
    }
}
