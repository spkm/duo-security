<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

class ExampleDeleteRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(protected string $userId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/examples/'.Path::segment($this->userId);
    }
}
