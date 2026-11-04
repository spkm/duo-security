<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Reset an OAuth client credentials application's client secret: POST /admin/v3/integrations/oauth_cc/{integration_key}/client_secret/{client_id}. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class ResetOAuthClientSecretRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $integrationKey, protected string $clientId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v3/integrations/oauth_cc/'.Path::segment($this->integrationKey).'/client_secret/'.Path::segment($this->clientId);
    }
}
