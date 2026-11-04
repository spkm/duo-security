<?php

namespace spkm\DuoSecurity\Admin\Requests\Settings;

use spkm\DuoSecurity\Requests\BinaryRequest;

/**
 * Retrieve the account's logo, as PNG bytes: GET /admin/v1/logo. Requires the "Grant settings" permission.
 */
class GetLogoRequest extends BinaryRequest
{
    public function resolveEndpoint(): string
    {
        return '/admin/v1/logo';
    }
}
