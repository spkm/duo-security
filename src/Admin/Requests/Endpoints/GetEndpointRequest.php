<?php

namespace spkm\DuoSecurity\Admin\Requests\Endpoints;

use spkm\DuoSecurity\Admin\Data\Endpoints\Endpoint;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve an endpoint: GET /admin/v1/endpoints/{epkey}. Requires the "Grant resource - Read" permission.
 */
class GetEndpointRequest extends GetRequest
{
    protected ?string $dto = Endpoint::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $epkey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/endpoints/'.Path::segment($this->epkey);
    }
}
