<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Requests\LogRequest;

class ExampleLogRequest extends LogRequest
{
    protected ?string $dto = ExampleData::class;

    protected ?string $itemsKey = 'items';

    public function resolveEndpoint(): string
    {
        return '/admin/v2/logs/examples';
    }
}
