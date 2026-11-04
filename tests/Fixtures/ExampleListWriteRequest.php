<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * A write Duo answers with a list of strings: creating bypass codes.
 */
class ExampleListWriteRequest extends WriteRequest
{
    protected string $responseShape = self::LIST;

    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(protected string $userId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/examples/'.Path::segment($this->userId).'/codes';
    }
}
