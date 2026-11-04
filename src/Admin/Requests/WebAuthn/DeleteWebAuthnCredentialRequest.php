<?php

namespace spkm\DuoSecurity\Admin\Requests\WebAuthn;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a WebAuthn credential: DELETE /admin/v1/webauthncredentials/{webauthnkey}. Requires the "Grant resource - Write" permission.
 */
class DeleteWebAuthnCredentialRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $webauthnkey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/webauthncredentials/'.Path::segment($this->webauthnkey);
    }
}
