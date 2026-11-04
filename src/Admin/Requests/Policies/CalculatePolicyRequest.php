<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use spkm\DuoSecurity\Admin\Data\Policies\PolicyCalculation;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Calculate the policy in effect for a user and an application: GET /admin/v2/policies/calculate. Requires the "Grant resource - Read" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class CalculatePolicyRequest extends GetRequest
{
    protected ?string $dto = PolicyCalculation::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(string $userId, string $integrationKey, array $params = [])
    {
        $this->params = ['user_id' => $userId, 'integration_key' => $integrationKey, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/policies/calculate';
    }
}
