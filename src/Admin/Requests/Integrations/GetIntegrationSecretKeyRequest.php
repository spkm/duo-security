<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Admin\Data\Integrations\SecretKey;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve an integration's secret key in full: GET /admin/v1/integrations/{integration_key}/skey. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class GetIntegrationSecretKeyRequest extends GetRequest
{
    protected ?string $dto = SecretKey::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $integrationKey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/integrations/'.Path::segment($this->integrationKey).'/skey';
    }
}
