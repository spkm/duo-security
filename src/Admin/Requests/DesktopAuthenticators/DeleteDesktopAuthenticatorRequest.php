<?php

namespace spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a Duo Desktop authenticator: DELETE /admin/v1/desktop_authenticators/{dakey}. Requires the "Grant resource - Write" permission.
 */
class DeleteDesktopAuthenticatorRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $dakey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/desktop_authenticators/'.Path::segment($this->dakey);
    }
}
