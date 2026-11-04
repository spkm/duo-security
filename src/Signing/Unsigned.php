<?php

namespace spkm\DuoSecurity\Signing;

use Saloon\Contracts\Authenticator;
use Saloon\Http\PendingRequest;

/**
 * No signature: for the one call Duo answers without one, the Auth API's /auth/v2/ping.
 */
final class Unsigned implements Authenticator
{
    public function set(PendingRequest $pendingRequest): void {}
}
