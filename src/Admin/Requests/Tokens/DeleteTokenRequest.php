<?php

namespace spkm\DuoSecurity\Admin\Requests\Tokens;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a hardware token (not one bought from Duo): DELETE /admin/v1/tokens/{token_id}. Requires the "Grant resource - Write" permission.
 */
class DeleteTokenRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $tokenId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/tokens/'.Path::segment($this->tokenId);
    }
}
