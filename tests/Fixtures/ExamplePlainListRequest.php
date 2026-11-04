<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Requests\ListRequest;

/**
 * A list without a model: its records are arrays (or strings, as bypass codes are).
 */
class ExamplePlainListRequest extends ListRequest
{
    protected int $maxPageSize = 500;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/examples/plain';
    }
}
