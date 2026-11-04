<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\Users\VerificationPushResponse;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve the answer to a verification push (gone 120 seconds after it was sent): GET /admin/v1/users/{user_id}/verification_push_response. Requires the "Grant resource - Read" permission.
 */
class GetUserVerificationPushResponseRequest extends GetRequest
{
    protected ?string $dto = VerificationPushResponse::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, string $pushId, array $params = [])
    {
        $this->params = ['push_id' => $pushId, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/verification_push_response';
    }
}
