<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\WebAuthn\WebAuthnCredential;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a user's WebAuthn credentials: GET /admin/v1/users/{user_id}/webauthncredentials. Requires the "Grant resource - Read" permission.
 */
class ListUserWebAuthnCredentialsRequest extends GetRequest
{
    protected ?string $dto = WebAuthnCredential::class;

    protected string $responseShape = self::LIST;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/webauthncredentials';
    }
}
