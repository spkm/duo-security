<?php

namespace spkm\DuoSecurity\Admin\Requests\Policies;

use Saloon\Enums\Method;
use spkm\DuoSecurity\Admin\Data\Policies\PolicyBatch;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Change sections of several policies at once: PUT /admin/v2/policies/update. Requires the "Grant resource - Write" permission.
 *
 * Needs v5 signing, which this package uses.
 *
 * Parameters Duo documents: policies_to_update, policy_changes.
 */
class UpdatePoliciesRequest extends WriteRequest
{
    protected ?string $dto = PolicyBatch::class;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: policies_to_update, policy_changes.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/policies/update';
    }
}
