<?php

namespace spkm\DuoSecurity\Admin\Requests\Tokens;

use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve U2F tokens (Duo now answers 403): GET /admin/v1/u2ftokens.
 *
 * @deprecated Duo deprecated this endpoint.
 */
class ListU2fTokensRequest extends GetRequest
{
    protected string $responseShape = self::LIST;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/u2ftokens';
    }
}
