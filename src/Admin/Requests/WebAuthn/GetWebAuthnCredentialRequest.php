<?php

namespace spkm\DuoSecurity\Admin\Requests\WebAuthn;

use spkm\DuoSecurity\Admin\Data\WebAuthn\WebAuthnCredential;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a WebAuthn credential: GET /admin/v1/webauthncredentials/{webauthnkey}. Requires the "Grant resource - Read" permission.
 */
class GetWebAuthnCredentialRequest extends GetRequest
{
    protected ?string $dto = WebAuthnCredential::class;

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
