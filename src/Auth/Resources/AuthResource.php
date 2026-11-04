<?php

namespace spkm\DuoSecurity\Auth\Resources;

use spkm\DuoSecurity\Auth\Data\AuthResult;
use spkm\DuoSecurity\Auth\Data\AuthStatus;
use spkm\DuoSecurity\Auth\Data\Enrollment;
use spkm\DuoSecurity\Auth\Data\PreauthResult;
use spkm\DuoSecurity\Auth\Data\ServerTime;
use spkm\DuoSecurity\Auth\Requests\AuthRequest;
use spkm\DuoSecurity\Auth\Requests\AuthStatusRequest;
use spkm\DuoSecurity\Auth\Requests\CheckRequest;
use spkm\DuoSecurity\Auth\Requests\EnrollRequest;
use spkm\DuoSecurity\Auth\Requests\EnrollStatusRequest;
use spkm\DuoSecurity\Auth\Requests\LogoRequest;
use spkm\DuoSecurity\Auth\Requests\PingRequest;
use spkm\DuoSecurity\Auth\Requests\PreauthRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Auth API's endpoints (https://duo.com/docs/authapi), from DuoSecurity::auth(): a check of the keys, enrollment, and the second factor itself.
 */
class AuthResource extends BaseResource
{
    /**
     * Check that Duo is up; the one call that is not signed: GET /auth/v2/ping.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function ping(array $params = []): ServerTime
    {
        return $this->fetch(new PingRequest($params));
    }

    /**
     * Check that the keys are valid and the signature correct: GET /auth/v2/check.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function check(array $params = []): ServerTime
    {
        return $this->fetch(new CheckRequest($params));
    }

    /**
     * Retrieve the application's logo, as PNG bytes: GET /auth/v2/logo.
     */
    public function logo(): string
    {
        return $this->fetch(new LogoRequest);
    }

    /**
     * Create a user and an activation code for Duo Mobile, as a QR code and a link: POST /auth/v2/enroll.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: username, valid_secs.
     */
    public function enroll(array $params = []): Enrollment
    {
        return $this->fetch(new EnrollRequest($params));
    }

    /**
     * Check whether an activation code has been claimed: success, invalid or waiting: POST /auth/v2/enroll_status.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function enrollStatus(string $userId, string $activationCode, array $params = []): mixed
    {
        return $this->fetch(new EnrollStatusRequest($userId, $activationCode, $params));
    }

    /**
     * Check whether a user may authenticate, and which devices and factors they have: POST /auth/v2/preauth.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: user_id, username, client_supports_verified_push, ipaddr, hostname, trusted_device_token.
     */
    public function preauth(array $params = []): PreauthResult
    {
        return $this->fetch(new PreauthRequest($params));
    }

    /**
     * Authenticate a user with a second factor: a push, a passcode, a phone call, or new SMS passcodes: POST /auth/v2/auth.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: user_id, username, factor, device, type, display_username, pushinfo, passcode, txid, ipaddr, hostname, async.
     */
    public function auth(array $params = []): AuthResult
    {
        return $this->fetch(new AuthRequest($params));
    }

    /**
     * Wait for the next status of an asynchronous authentication (a long poll): GET /auth/v2/auth_status.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function authStatus(string $txid, array $params = []): AuthStatus
    {
        return $this->fetch(new AuthStatusRequest($txid, $params));
    }
}
