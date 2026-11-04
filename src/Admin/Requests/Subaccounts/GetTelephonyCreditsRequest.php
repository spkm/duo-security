<?php

namespace spkm\DuoSecurity\Admin\Requests\Subaccounts;

use spkm\DuoSecurity\Admin\Data\Subaccounts\TelephonyCredits;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve a subaccount's telephony credits: GET /admin/v1/billing/telephony_credits. Requires the "Grant accounts - Read" permission.
 */
class GetTelephonyCreditsRequest extends GetRequest
{
    protected ?string $dto = TelephonyCredits::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(string $accountId, array $params = [])
    {
        $this->params = ['account_id' => $accountId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/billing/telephony_credits';
    }
}
