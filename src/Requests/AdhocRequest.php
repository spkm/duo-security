<?php

namespace spkm\DuoSecurity\Requests;

use Saloon\Enums\Method;
use spkm\DuoSecurity\Support\Path;
use spkm\DuoSecurity\Support\Payload;

/**
 * Any call to Duo by method and path, for an endpoint the package has no request for, from DuoSecurity::get()
 * and DuoSecurity::delete(). dto() gives the response as decoded, or one record as an array when built with the
 * ONE shape. AdhocBodyRequest carries a JSON body too.
 */
class AdhocRequest extends DuoRequest
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    public function __construct(
        protected Method $method,
        protected string $path,
        protected array $parameters = [],
        string $shape = self::RAW,
    ) {
        $this->responseShape = $shape;
        $this->safe = $method === Method::GET;
    }

    public function resolveEndpoint(): string
    {
        return Path::api($this->path);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return Payload::parameters($this->parameters);
    }
}
