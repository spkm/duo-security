<?php

namespace spkm\DuoSecurity\Admin\Requests\IdentityVerification;

use spkm\DuoSecurity\Admin\Data\IdentityVerification\IdentityVerification;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Start an identity verification for a user: an access code to redirect them with: POST /admin/v2/identity_verification/{user_id}/start. Requires the "Grant identity verification - Write" permission.
 */
class StartIdentityVerificationRequest extends WriteRequest
{
    protected ?string $dto = IdentityVerification::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/identity_verification/'.Path::segment($this->userId).'/start';
    }
}
