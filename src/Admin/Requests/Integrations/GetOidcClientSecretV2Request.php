<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Admin\Data\Integrations\ClientSecret;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve an OIDC application's client secret (v2): GET /admin/v2/integrations/oidc/{integration_key}/client_secret. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class GetOidcClientSecretV2Request extends GetRequest
{
    protected ?string $dto = ClientSecret::class;

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
