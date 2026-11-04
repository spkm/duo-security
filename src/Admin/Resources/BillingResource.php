<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Subaccounts\Edition;
use spkm\DuoSecurity\Admin\Data\Subaccounts\TelephonyCredits;
use spkm\DuoSecurity\Admin\Data\Subaccounts\UserLimit;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\GetEditionRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\GetTelephonyCreditsRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\GetUserLimitRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\SetEditionRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\SetTelephonyCreditsRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\SetUserLimitRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Subaccounts endpoints of the Admin API, from DuoSecurity::billing().
 */
class BillingResource extends BaseResource
{
    /**
     * Retrieve a subaccount's edition: GET /admin/v1/billing/edition.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getEdition(string $accountId, array $params = []): Edition
    {
        return $this->fetch(new GetEditionRequest($accountId, $params));
    }

    /**
     * Set a subaccount's edition (ENTERPRISE, PLATFORM or BEYOND): POST /admin/v1/billing/edition.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function setEdition(string $accountId, string $edition, array $params = []): bool
    {
        return $this->perform(new SetEditionRequest($accountId, $edition, $params));
    }

    /**
     * Retrieve a subaccount's telephony credits: GET /admin/v1/billing/telephony_credits.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getTelephonyCredits(string $accountId, array $params = []): TelephonyCredits
    {
        return $this->fetch(new GetTelephonyCreditsRequest($accountId, $params));
    }

    /**
     * Set a subaccount's telephony credits, moved from the parent account: POST /admin/v1/billing/telephony_credits.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: credits.
     */
    public function setTelephonyCredits(string $accountId, array $params = []): TelephonyCredits
    {
        return $this->fetch(new SetTelephonyCreditsRequest($accountId, $params));
    }

    /**
     * Retrieve a subaccount's user count and hard user limit: GET /admin/v1/billing/user_limit.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getUserLimit(string $accountId, array $params = []): UserLimit
    {
        return $this->fetch(new GetUserLimitRequest($accountId, $params));
    }

    /**
     * Set a subaccount's hard user limit (0 removes it): POST /admin/v1/billing/user_limit.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: user_limit.
     */
    public function setUserLimit(string $accountId, array $params = []): bool
    {
        return $this->perform(new SetUserLimitRequest($accountId, $params));
    }
}
