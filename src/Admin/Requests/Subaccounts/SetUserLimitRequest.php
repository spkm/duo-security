<?php

namespace spkm\DuoSecurity\Admin\Requests\Subaccounts;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Set a subaccount's hard user limit (0 removes it): POST /admin/v1/billing/user_limit. Requires the "Grant user limits - Write" permission.
 *
 * Parameters Duo documents: user_limit.
 */
class SetUserLimitRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: user_limit.
     */
    public function __construct(string $accountId, array $params = [])
    {
        $this->params = ['account_id' => $accountId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/billing/user_limit';
    }
}
