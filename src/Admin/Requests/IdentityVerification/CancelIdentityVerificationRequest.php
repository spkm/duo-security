<?php

namespace spkm\DuoSecurity\Admin\Requests\IdentityVerification;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Cancel a user's identity verification in progress: POST /admin/v2/identity_verification/{user_id}/cancel. Requires the "Grant identity verification - Write" permission.
 */
class CancelIdentityVerificationRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, string $inquiryId, array $params = [])
    {
        $this->params = ['inquiry_id' => $inquiryId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/identity_verification/'.Path::segment($this->userId).'/cancel';
    }
}
