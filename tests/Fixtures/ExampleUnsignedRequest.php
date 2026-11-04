<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use Saloon\Contracts\Authenticator;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Signing\Unsigned;

class ExampleUnsignedRequest extends GetRequest
{
    protected string $responseShape = self::RAW;

    protected function defaultAuth(): ?Authenticator
    {
        return new Unsigned;
    }

    public function resolveEndpoint(): string
    {
        return '/auth/v2/ping';
    }
}
