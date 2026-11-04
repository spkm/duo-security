<?php

namespace spkm\DuoSecurity\Admin\Requests\Subaccounts;

use spkm\DuoSecurity\Admin\Data\Subaccounts\TelephonyCredits;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Set a subaccount's telephony credits, moved from the parent account: POST /admin/v1/billing/telephony_credits. Requires the "Grant accounts - Write" permission.
 *
 * Parameters Duo documents: credits.
 */
class SetTelephonyCreditsRequest extends WriteRequest
{
    protected ?string $dto = TelephonyCredits::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: credits.
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
