<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use spkm\DuoSecurity\Admin\Data\Policies\PolicyBatch;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Copy a policy into new, unassigned policies: POST /admin/v2/policies/copy. Requires the "Grant resource - Write" permission.
 *
 * Needs v5 signing, which this package uses.
 *
 * Parameters Duo documents: new_policy_names_list.
 */
class CopyPolicyRequest extends WriteRequest
{
    protected ?string $dto = PolicyBatch::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: new_policy_names_list.
     */
    public function __construct(string $policyKey, array $params = [])
    {
        $this->params = ['policy_key' => $policyKey, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/policies/copy';
    }
}
