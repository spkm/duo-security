<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a custom policy (not the global one): DELETE /admin/v2/policies/{policy_key}. Requires the "Grant resource - Write" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class DeletePolicyRequest extends DeleteRequest
{
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
