<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Requests\BinaryRequest;

class ExampleBinaryRequest extends BinaryRequest
{
    public function resolveEndpoint(): string
    {
        return '/admin/v1/examples/logo';
    }
}
