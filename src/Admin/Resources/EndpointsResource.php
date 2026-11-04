<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Endpoints\Endpoint;
use spkm\DuoSecurity\Admin\Requests\Endpoints\GetEndpointRequest;
use spkm\DuoSecurity\Admin\Requests\Endpoints\ListEndpointsRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Endpoints endpoints of the Admin API, from DuoSecurity::endpoints().
 */
class EndpointsResource extends BaseResource
{
    /**
     * Retrieve endpoints (Duo Premier and Advantage): GET /admin/v1/endpoints.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<Endpoint>
     */
    public function listEndpoints(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListEndpointsRequest([...$params, ...$page]), Endpoint::class);
    }

    /**
     * Retrieve an endpoint: GET /admin/v1/endpoints/{epkey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getEndpoint(string $epkey, array $params = []): Endpoint
    {
        return $this->fetch(new GetEndpointRequest($epkey, $params));
    }
}
