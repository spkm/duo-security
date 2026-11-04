<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\IdentityVerification\IdentityVerification;
use spkm\DuoSecurity\Admin\Requests\IdentityVerification\CancelIdentityVerificationRequest;
use spkm\DuoSecurity\Admin\Requests\IdentityVerification\GetIdentityVerificationStatusRequest;
use spkm\DuoSecurity\Admin\Requests\IdentityVerification\StartIdentityVerificationRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Identity Verification endpoints of the Admin API, from DuoSecurity::identityVerification().
 */
class IdentityVerificationResource extends BaseResource
{
    /**
     * Start an identity verification for a user: an access code to redirect them with: POST /admin/v2/identity_verification/{user_id}/start.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function startIdentityVerification(string $userId, array $params = []): IdentityVerification
    {
        return $this->fetch(new StartIdentityVerificationRequest($userId, $params));
    }

    /**
     * Retrieve the status of a user's latest identity verification: GET /admin/v2/identity_verification/{user_id}/status.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getIdentityVerificationStatus(string $userId, array $params = []): IdentityVerification
    {
        return $this->fetch(new GetIdentityVerificationStatusRequest($userId, $params));
    }

    /**
     * Cancel a user's identity verification in progress: POST /admin/v2/identity_verification/{user_id}/cancel.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function cancelIdentityVerification(string $userId, string $inquiryId, array $params = []): bool
    {
        return $this->perform(new CancelIdentityVerificationRequest($userId, $inquiryId, $params));
    }
}
