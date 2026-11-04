<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create a user and email them an enrollment link; the enrollment code is returned: POST /admin/v1/users/enroll. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: valid_secs.
 */
class EnrollUserRequest extends WriteRequest
{
    protected string $responseShape = self::RAW;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: valid_secs.
     */
    public function __construct(string $username, string $email, array $params = [])
    {
        $this->params = ['username' => $username, 'email' => $email, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/enroll';
    }
}
