<?php

namespace spkm\DuoSecurity\Auth\Requests;

use spkm\DuoSecurity\Auth\Data\AuthStatus;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Wait for the next status of an asynchronous authentication (a long poll): GET /auth/v2/auth_status.
 */
class AuthStatusRequest extends GetRequest
{
    protected ?string $dto = AuthStatus::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(string $txid, array $params = [])
    {
        $this->params = ['txid' => $txid, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/auth/v2/auth_status';
    }
}
