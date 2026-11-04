<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Reset an OIDC application's client secret: POST /admin/v3/integrations/oidc/{integration_key}/client_secret. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class ResetOidcClientSecretRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $integrationKey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v3/integrations/oidc/'.Path::segment($this->integrationKey).'/client_secret';
    }
}
