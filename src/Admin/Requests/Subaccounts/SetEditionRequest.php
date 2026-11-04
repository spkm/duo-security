<?php

namespace spkm\DuoSecurity\Admin\Requests\Subaccounts;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Set a subaccount's edition (ENTERPRISE, PLATFORM or BEYOND): POST /admin/v1/billing/edition. Requires the "Grant accounts - Write" permission.
 */
class SetEditionRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(string $accountId, string $edition, array $params = [])
    {
        $this->params = ['account_id' => $accountId, 'edition' => $edition, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/billing/edition';
    }
}
