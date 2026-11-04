<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a user's U2F tokens (Duo now answers 403): GET /admin/v1/users/{user_id}/u2ftokens.
 *
 * @deprecated Duo deprecated this endpoint.
 */
class ListUserU2fTokensRequest extends GetRequest
{
    protected string $responseShape = self::LIST;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/u2ftokens';
    }
}
