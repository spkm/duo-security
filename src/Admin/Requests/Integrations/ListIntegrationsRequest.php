<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Admin\Data\Integrations\Integration;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve integrations (applications): GET /admin/v3/integrations. Requires the "Grant resource - Read" permission.
 *
 * Needs v5 signing, which this package uses.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListIntegrationsRequest extends ListRequest
{
    protected ?string $dto = Integration::class;

    protected int $maxPageSize = 500;

    public function resolveEndpoint(): string
    {
        return '/admin/v3/integrations';
    }
}
