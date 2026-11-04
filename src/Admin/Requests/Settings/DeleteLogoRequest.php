<?php

namespace spkm\DuoSecurity\Admin\Requests\Settings;

use spkm\DuoSecurity\Requests\DeleteRequest;

/**
 * Delete the account's logo: DELETE /admin/v1/logo. Requires the "Grant settings" permission.
 */
class DeleteLogoRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/logo';
    }
}
