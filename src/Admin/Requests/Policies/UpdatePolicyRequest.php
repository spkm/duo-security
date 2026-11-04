<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use Saloon\Enums\Method;
use spkm\DuoSecurity\Admin\Data\Policies\Policy;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Update a policy: PUT /admin/v2/policies/{policy_key}. Requires the "Grant resource - Write" permission.
 *
 * Needs v5 signing, which this package uses.
 *
 * Parameters Duo documents: policy_name, sections, sections_to_delete, apply_to_apps, apply_to_groups_in_apps, apply_to_user_groups.
 */
class UpdatePolicyRequest extends WriteRequest
{
    protected ?string $dto = Policy::class;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: policy_name, sections, sections_to_delete, apply_to_apps, apply_to_groups_in_apps, apply_to_user_groups.
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
