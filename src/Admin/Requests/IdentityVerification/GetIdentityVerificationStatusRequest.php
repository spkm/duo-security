<?php

namespace spkm\DuoSecurity\Admin\Requests\IdentityVerification;

use spkm\DuoSecurity\Admin\Data\IdentityVerification\IdentityVerification;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve the status of a user's latest identity verification: GET /admin/v2/identity_verification/{user_id}/status. Requires the "Grant identity verification - Read" permission.
 */
class GetIdentityVerificationStatusRequest extends GetRequest
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
        return '/admin/v2/identity_verification/'.Path::segment($this->userId).'/status';
    }
}
