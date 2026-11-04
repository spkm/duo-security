<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use spkm\DuoSecurity\Admin\Data\Policies\Policy;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create a policy: POST /admin/v2/policies. Requires the "Grant resource - Write" permission.
 *
 * Needs v5 signing, which this package uses.
 *
 * Parameters Duo documents: apply_to_apps, apply_to_groups_in_apps, apply_to_user_groups, sections.
 */
class CreatePolicyRequest extends WriteRequest
{
    protected ?string $dto = Policy::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: apply_to_apps, apply_to_groups_in_apps, apply_to_user_groups, sections.
     */
    public function __construct(string $policyName, array $params = [])
    {
        $this->params = ['policy_name' => $policyName, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/policies';
    }
}
