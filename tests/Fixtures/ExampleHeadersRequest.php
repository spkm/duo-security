<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Requests\GetRequest;

/**
 * A request that sends X-Duo-* headers, which are signed too.
 */
class ExampleHeadersRequest extends GetRequest
{
    protected string $responseShape = self::RAW;

    /**
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return ['X-Duo-Request-Id' => ' abc-123 ', 'x-duo-other' => 'two'];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/examples';
    }
}
