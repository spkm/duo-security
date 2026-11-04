<?php

namespace spkm\DuoSecurity\Admin\Requests\Tokens;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Resynchronize a hardware token with three codes in a row: POST /admin/v1/tokens/{token_id}/resync. Requires the "Grant resource - Write" permission.
 */
class ResyncTokenRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $tokenId, string $code1, string $code2, string $code3, array $params = [])
    {
        $this->params = ['code1' => $code1, 'code2' => $code2, 'code3' => $code3, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/tokens/'.Path::segment($this->tokenId).'/resync';
    }
}
