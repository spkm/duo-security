<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use JsonSerializable;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\Requests\WriteRequest;

class ExampleWriteRequest extends WriteRequest
{
    protected ?string $dto = ExampleData::class;

    /**
     * @param  array<string, mixed>|Data|JsonSerializable  $params
     */
    public function __construct(array|Data|JsonSerializable $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/examples';
    }
}
