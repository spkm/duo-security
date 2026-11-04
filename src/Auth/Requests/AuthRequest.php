<?php

namespace spkm\DuoSecurity\Auth\Requests;

use spkm\DuoSecurity\Auth\Data\AuthResult;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Authenticate a user with a second factor: a push, a passcode, a phone call, or new SMS passcodes: POST /auth/v2/auth.
 *
 * Parameters Duo documents: user_id, username, factor, device, type, display_username, pushinfo, passcode, txid, ipaddr, hostname, async.
 */
class AuthRequest extends WriteRequest
{
    protected ?string $dto = AuthResult::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: user_id, username, factor, device, type, display_username, pushinfo, passcode, txid, ipaddr, hostname, async.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/auth/v2/auth';
    }
}
