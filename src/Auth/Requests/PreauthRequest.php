<?php

namespace spkm\DuoSecurity\Auth\Requests;

use spkm\DuoSecurity\Auth\Data\PreauthResult;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Check whether a user may authenticate, and which devices and factors they have: POST /auth/v2/preauth.
 *
 * Parameters Duo documents: user_id, username, client_supports_verified_push, ipaddr, hostname, trusted_device_token.
 */
class PreauthRequest extends WriteRequest
{
    protected ?string $dto = PreauthResult::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: user_id, username, client_supports_verified_push, ipaddr, hostname, trusted_device_token.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/auth/v2/preauth';
    }
}
