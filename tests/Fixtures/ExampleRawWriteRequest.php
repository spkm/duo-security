<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * A write Duo answers with a string: an enrollment code.
 */
class ExampleRawWriteRequest extends WriteRequest
{
    protected string $responseShape = self::RAW;

    /**
     * @param  array<string, mixed>  $params
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/examples/enroll';
    }
}
