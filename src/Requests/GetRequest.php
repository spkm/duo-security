<?php

namespace spkm\DuoSecurity\Requests;

use Saloon\Enums\Method;
use spkm\DuoSecurity\Support\Payload;

/**
 * A GET for one record, a setting or a report, with any parameters the endpoint documents in the query string.
 */
abstract class GetRequest extends DuoRequest
{
    protected Method $method = Method::GET;

    protected string $responseShape = self::ONE;

    /** @var array<string, mixed> */
    protected array $params = [];

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return Payload::parameters($this->params);
    }
}
