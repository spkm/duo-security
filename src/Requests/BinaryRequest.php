<?php

namespace spkm\DuoSecurity\Requests;

use Saloon\Enums\Method;

/**
 * A GET for a file rather than JSON: a logo as PNG bytes. dto() gives the body as a string.
 */
abstract class BinaryRequest extends DuoRequest
{
    protected Method $method = Method::GET;

    protected string $responseShape = self::TEXT;

    /**
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return ['Accept' => '*/*'];
    }
}
