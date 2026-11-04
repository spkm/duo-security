<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete an integration (v2; Duo recommends v3): DELETE /admin/v2/integrations/{integration_key}. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class DeleteIntegrationV2Request extends DeleteRequest
{
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
