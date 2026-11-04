<?php

namespace spkm\DuoSecurity\Admin\Requests\WebAuthn;

use spkm\DuoSecurity\Admin\Data\WebAuthn\WebAuthnCredential;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve the WebAuthn credentials of users and administrators: GET /admin/v1/webauthncredentials. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListWebAuthnCredentialsRequest extends ListRequest
{
    protected ?string $dto = WebAuthnCredential::class;

    protected int $maxPageSize = 500;

    protected bool $cacheable = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/webauthncredentials';
    }
}
