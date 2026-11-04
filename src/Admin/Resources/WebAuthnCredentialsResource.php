<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\WebAuthn\WebAuthnCredential;
use spkm\DuoSecurity\Admin\Requests\WebAuthn\DeleteWebAuthnCredentialRequest;
use spkm\DuoSecurity\Admin\Requests\WebAuthn\GetWebAuthnCredentialRequest;
use spkm\DuoSecurity\Admin\Requests\WebAuthn\ListWebAuthnCredentialsRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The WebAuthn Credentials endpoints of the Admin API, from DuoSecurity::webAuthnCredentials().
 */
class WebAuthnCredentialsResource extends BaseResource
{
    /**
     * Retrieve the WebAuthn credentials of users and administrators: GET /admin/v1/webauthncredentials.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<WebAuthnCredential>
     */
    public function listWebAuthnCredentials(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListWebAuthnCredentialsRequest([...$params, ...$page]), WebAuthnCredential::class);
    }

    /**
     * Retrieve a WebAuthn credential: GET /admin/v1/webauthncredentials/{webauthnkey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getWebAuthnCredential(string $webauthnkey, array $params = []): WebAuthnCredential
    {
        return $this->fetch(new GetWebAuthnCredentialRequest($webauthnkey, $params));
    }

    /**
     * Delete a WebAuthn credential: DELETE /admin/v1/webauthncredentials/{webauthnkey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteWebAuthnCredential(string $webauthnkey, array $params = []): bool
    {
        return $this->perform(new DeleteWebAuthnCredentialRequest($webauthnkey, $params));
    }
}
