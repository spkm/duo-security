<?php

namespace spkm\DuoSecurity\Requests;

use Saloon\Enums\Method;
use spkm\DuoSecurity\Support\Payload;

/**
 * A DELETE of one record or link, with any parameters in the query string. Duo answers "" (and 200 even when
 * there was nothing to delete).
 */
abstract class DeleteRequest extends DuoRequest
{
    protected Method $method = Method::DELETE;

    protected string $responseShape = self::NONE;

    protected bool $safe = false;

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
