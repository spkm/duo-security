<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Admin\Data\Integrations\Integration;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve an integration (v2; Duo recommends v3): GET /admin/v2/integrations/{integration_key}. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class GetIntegrationV2Request extends GetRequest
{
    protected ?string $dto = Integration::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $integrationKey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/integrations/'.Path::segment($this->integrationKey);
    }
}
