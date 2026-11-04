<?php

namespace spkm\DuoSecurity\Auth\Requests;

use spkm\DuoSecurity\Requests\BinaryRequest;

/**
 * Retrieve the application's logo, as PNG bytes: GET /auth/v2/logo.
 */
class LogoRequest extends BinaryRequest
{
    public function resolveEndpoint(): string
    {
        return '/auth/v2/logo';
    }
}
