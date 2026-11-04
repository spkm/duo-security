<?php

namespace spkm\DuoSecurity\Auth;

use spkm\DuoSecurity\Auth\Resources\AuthResource;
use spkm\DuoSecurity\DuoConnector;
use spkm\DuoSecurity\Signing\Credentials;

/**
 * The connection to an Auth API application (https://duo.com/docs/authapi): the second factor itself, from
 * checking the keys to sending a push and waiting for the answer. /auth/v2/auth_status long-polls for up to a
 * minute, so the request timeout is at least 70 seconds.
 */
class AuthConnector extends DuoConnector
{
    public const LONG_POLL_TIMEOUT = 70.0;

    public function __construct(Credentials $credentials)
    {
        parent::__construct($credentials);

        $this->requestTimeout = max($this->requestTimeout, self::LONG_POLL_TIMEOUT);
    }

    /**
     * The Auth API's endpoints.
     */
    public function auth(): AuthResource
    {
        return new AuthResource($this);
    }
}
