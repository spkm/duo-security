<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create up to 100 users in one call (at most 50 calls a minute): POST /admin/v1/users/bulk_create. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: users.
 */
class BulkCreateUsersRequest extends WriteRequest
{
    protected ?string $dto = User::class;

    protected string $responseShape = self::LIST;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: users.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/bulk_create';
    }
}
