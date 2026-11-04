<?php

namespace spkm\DuoSecurity\Auth\Requests;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Check whether an activation code has been claimed: success, invalid or waiting: POST /auth/v2/enroll_status.
 */
class EnrollStatusRequest extends WriteRequest
{
    protected string $responseShape = self::RAW;

    protected bool $safe = true;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(string $userId, string $activationCode, array $params = [])
    {
        $this->params = ['user_id' => $userId, 'activation_code' => $activationCode, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/auth/v2/enroll_status';
    }
}
