<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Requests\ListRequest;

class ExampleListRequest extends ListRequest
{
    protected ?string $dto = ExampleData::class;

    protected int $maxPageSize = 3;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/examples';
    }
}
