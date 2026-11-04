<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Admin\Data\Integrations\ClientSecret;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve an OAuth client credentials application's client secret: GET /admin/v3/integrations/oauth_cc/{integration_key}/client_secret/{client_id}. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class GetOAuthClientSecretRequest extends GetRequest
{
    protected ?string $dto = ClientSecret::class;

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
