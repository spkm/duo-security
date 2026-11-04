<?php

namespace spkm\DuoSecurity\Admin\Requests\Subaccounts;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Delete a subaccount (409 when it has subaccounts of its own): POST /accounts/v1/account/delete. Requires the "Grant accounts - Write" permission.
 */
class DeleteSubaccountRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(string $accountId, array $params = [])
    {
        $this->params = ['account_id' => $accountId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/accounts/v1/account/delete';
    }
}
