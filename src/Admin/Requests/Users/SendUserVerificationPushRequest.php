<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\Users\VerificationPush;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Send a verification Duo Push to one of a user's phones: POST /admin/v1/users/{user_id}/send_verification_push. Requires the "Grant resource - Write" permission.
 */
class SendUserVerificationPushRequest extends WriteRequest
{
    protected ?string $dto = VerificationPush::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, string $phoneId, array $params = [])
    {
        $this->params = ['phone_id' => $phoneId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/send_verification_push';
    }
}
