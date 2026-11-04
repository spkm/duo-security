<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Create bypass codes for a user, replacing their existing ones unless preserve_existing is set; the codes are returned: POST /admin/v1/users/{user_id}/bypass_codes. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: count, codes, preserve_existing, reuse_count, valid_secs.
 */
class CreateUserBypassCodesRequest extends WriteRequest
{
    protected string $responseShape = self::LIST;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: count, codes, preserve_existing, reuse_count, valid_secs.
     */
    public function __construct(protected string $userId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/bypass_codes';
    }
}
