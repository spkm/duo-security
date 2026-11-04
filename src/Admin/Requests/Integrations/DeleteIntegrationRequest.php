<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete an integration (remove Duo from the application first, or its logins fail): DELETE /admin/v3/integrations/{integration_key}. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class DeleteIntegrationRequest extends DeleteRequest
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
        return '/admin/v3/integrations/'.Path::segment($this->integrationKey);
    }
}
