<?php

namespace spkm\DuoSecurity\Admin\Requests\Subaccounts;

use spkm\DuoSecurity\Admin\Data\Subaccounts\UserLimit;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve a subaccount's user count and hard user limit: GET /admin/v1/billing/user_limit. Requires the "Grant user limits - Read" permission.
 */
class GetUserLimitRequest extends GetRequest
{
    protected ?string $dto = UserLimit::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
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
