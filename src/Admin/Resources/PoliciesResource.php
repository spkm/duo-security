<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Policies\Policy;
use spkm\DuoSecurity\Admin\Data\Policies\PolicyBatch;
use spkm\DuoSecurity\Admin\Data\Policies\PolicyCalculation;
use spkm\DuoSecurity\Admin\Data\Policies\PolicySummary;
use spkm\DuoSecurity\Admin\Requests\Policies\CalculatePolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\CopyPolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\CreatePolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\DeletePolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\GetGlobalPolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\GetPoliciesSummaryRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\GetPolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\ListPoliciesRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\UpdatePoliciesRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\UpdatePolicyRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Policies endpoints of the Admin API, from DuoSecurity::policies().
 */
class PoliciesResource extends BaseResource
{
    /**
     * Retrieve every policy's name, key and where it applies: GET /admin/v2/policies/summary.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getPoliciesSummary(array $params = []): PolicySummary
    {
        return $this->fetch(new GetPoliciesSummaryRequest($params));
    }

    /**
     * Retrieve policies with their sections: GET /admin/v2/policies.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<Policy>
     */
    public function listPolicies(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListPoliciesRequest([...$params, ...$page]), Policy::class);
    }

    /**
     * Retrieve a policy: GET /admin/v2/policies/{policy_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getPolicy(string $policyKey, array $params = []): Policy
    {
        return $this->fetch(new GetPolicyRequest($policyKey, $params));
    }

    /**
     * Retrieve the global policy: GET /admin/v2/policies/global.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getGlobalPolicy(array $params = []): Policy
    {
        return $this->fetch(new GetGlobalPolicyRequest($params));
    }

    /**
     * Calculate the policy in effect for a user and an application: GET /admin/v2/policies/calculate.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function calculatePolicy(string $userId, string $integrationKey, array $params = []): PolicyCalculation
    {
        return $this->fetch(new CalculatePolicyRequest($userId, $integrationKey, $params));
    }

    /**
     * Copy a policy into new, unassigned policies: POST /admin/v2/policies/copy.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: new_policy_names_list.
     */
    public function copyPolicy(string $policyKey, array $params = []): PolicyBatch
    {
        return $this->fetch(new CopyPolicyRequest($policyKey, $params));
    }

    /**
     * Create a policy: POST /admin/v2/policies.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: apply_to_apps, apply_to_groups_in_apps, apply_to_user_groups, sections.
     */
    public function createPolicy(string $policyName, array $params = []): Policy
    {
        return $this->fetch(new CreatePolicyRequest($policyName, $params));
    }

    /**
     * Change sections of several policies at once: PUT /admin/v2/policies/update.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: policies_to_update, policy_changes.
     */
    public function updatePolicies(array $params = []): PolicyBatch
    {
        return $this->fetch(new UpdatePoliciesRequest($params));
    }

    /**
     * Update a policy: PUT /admin/v2/policies/{policy_key}.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: policy_name, sections, sections_to_delete, apply_to_apps, apply_to_groups_in_apps, apply_to_user_groups.
     */
    public function updatePolicy(string $policyKey, array $params = []): Policy
    {
        return $this->fetch(new UpdatePolicyRequest($policyKey, $params));
    }

    /**
     * Delete a custom policy (not the global one): DELETE /admin/v2/policies/{policy_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deletePolicy(string $policyKey, array $params = []): bool
    {
        return $this->perform(new DeletePolicyRequest($policyKey, $params));
    }
}
