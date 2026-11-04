<?php

namespace spkm\DuoSecurity\Admin\Requests\Tokens;

use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a hardware token: GET /admin/v1/tokens/{token_id}. Requires the "Grant resource - Read" permission.
 */
class GetTokenRequest extends GetRequest
{
    protected ?string $dto = Token::class;

    protected bool $cacheable = true;

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
