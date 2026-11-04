<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Reset an OIDC application's client secret (v2): POST /admin/v2/integrations/oidc/{integration_key}/client_secret. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class ResetOidcClientSecretV2Request extends WriteRequest
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
        return '/admin/v2/integrations/oidc/'.Path::segment($this->integrationKey).'/client_secret';
    }
}
