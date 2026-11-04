<?php

namespace spkm\DuoSecurity\Auth\Requests;

use spkm\DuoSecurity\Auth\Data\ServerTime;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Check that the keys are valid and the signature correct: GET /auth/v2/check.
 */
class CheckRequest extends GetRequest
{
    protected ?string $dto = ServerTime::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/auth/v2/check';
    }
}
