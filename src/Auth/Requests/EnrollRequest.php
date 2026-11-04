<?php

namespace spkm\DuoSecurity\Auth\Requests;

use spkm\DuoSecurity\Auth\Data\Enrollment;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create a user and an activation code for Duo Mobile, as a QR code and a link: POST /auth/v2/enroll.
 *
 * Parameters Duo documents: username, valid_secs.
 */
class EnrollRequest extends WriteRequest
{
    protected ?string $dto = Enrollment::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: username, valid_secs.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/auth/v2/enroll';
    }
}
