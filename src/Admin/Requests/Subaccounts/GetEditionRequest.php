<?php

namespace spkm\DuoSecurity\Admin\Requests\Subaccounts;

use spkm\DuoSecurity\Admin\Data\Subaccounts\Edition;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve a subaccount's edition: GET /admin/v1/billing/edition. Requires the "Grant accounts - Read" permission.
 */
class GetEditionRequest extends GetRequest
{
    protected ?string $dto = Edition::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(string $accountId, array $params = [])
    {
        $this->params = ['account_id' => $accountId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/billing/edition';
    }
}
