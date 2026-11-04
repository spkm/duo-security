<?php

namespace spkm\DuoSecurity\Admin\Requests\Info;

use spkm\DuoSecurity\Admin\Data\Info\AuthenticationAttempts;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the counts of authentication attempts in a period, by result (times in seconds): GET /admin/v1/info/authentication_attempts. Requires the "Grant read information" permission.
 *
 * Parameters Duo documents: mintime, maxtime.
 */
class GetAuthenticationAttemptsRequest extends GetRequest
{
    protected ?string $dto = AuthenticationAttempts::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/info/authentication_attempts';
    }
}
