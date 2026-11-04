<?php

namespace spkm\DuoSecurity\Auth\Requests;

use Saloon\Contracts\Authenticator;
use spkm\DuoSecurity\Auth\Data\ServerTime;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Signing\Unsigned;

/**
 * Check that Duo is up; the one call that is not signed: GET /auth/v2/ping.
 */
class PingRequest extends GetRequest
{
    protected ?string $dto = ServerTime::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    protected function defaultAuth(): ?Authenticator
    {
        return new Unsigned;
    }

    public function resolveEndpoint(): string
    {
        return '/auth/v2/ping';
    }
}
