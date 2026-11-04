<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Info\AccountSummary;
use spkm\DuoSecurity\Admin\Data\Info\AuthenticationAttempts;
use spkm\DuoSecurity\Admin\Data\Info\DashboardSummary;
use spkm\DuoSecurity\Admin\Data\Info\TelephonyCreditsUsed;
use spkm\DuoSecurity\Admin\Data\Info\UserAuthenticationAttempts;
use spkm\DuoSecurity\Admin\Requests\Info\GetAccountSummaryRequest;
use spkm\DuoSecurity\Admin\Requests\Info\GetAuthenticationAttemptsRequest;
use spkm\DuoSecurity\Admin\Requests\Info\GetDashboardSummaryRequest;
use spkm\DuoSecurity\Admin\Requests\Info\GetTelephonyCreditsUsedRequest;
use spkm\DuoSecurity\Admin\Requests\Info\GetUserAuthenticationAttemptsRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Account Info endpoints of the Admin API, from DuoSecurity::info().
 */
class InfoResource extends BaseResource
{
    /**
     * Retrieve the account's counts of users, admins, integrations, groups, phones and tokens: GET /admin/v1/info/summary.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getAccountSummary(array $params = []): AccountSummary
    {
        return $this->fetch(new GetAccountSummaryRequest($params));
    }

    /**
     * Retrieve the Admin Panel dashboard's summary for a period (times in seconds): GET /admin/v1/info/dashboard_summary.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime.
     */
    public function getDashboardSummary(array $params = []): DashboardSummary
    {
        return $this->fetch(new GetDashboardSummaryRequest($params));
    }

    /**
     * Retrieve the telephony credits used in a period (times in seconds): GET /admin/v1/info/telephony_credits_used.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime.
     */
    public function getTelephonyCreditsUsed(array $params = []): TelephonyCreditsUsed
    {
        return $this->fetch(new GetTelephonyCreditsUsedRequest($params));
    }

    /**
     * Retrieve the counts of authentication attempts in a period, by result (times in seconds): GET /admin/v1/info/authentication_attempts.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime.
     */
    public function getAuthenticationAttempts(array $params = []): AuthenticationAttempts
    {
        return $this->fetch(new GetAuthenticationAttemptsRequest($params));
    }

    /**
     * Retrieve the counts of users with authentication attempts in a period, by result (times in seconds): GET /admin/v1/info/user_authentication_attempts.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime.
     */
    public function getUserAuthenticationAttempts(array $params = []): UserAuthenticationAttempts
    {
        return $this->fetch(new GetUserAuthenticationAttemptsRequest($params));
    }
}
