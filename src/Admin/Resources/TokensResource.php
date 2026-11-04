<?php

namespace spkm\DuoSecurity\Admin\Resources;

use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Admin\Requests\Tokens\CreateTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\DeleteTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\GetTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\ListTokensRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\ListU2fTokensRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\ResyncTokenRequest;
use spkm\DuoSecurity\Admin\Resources\Concerns\FindsTokens;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Hardware Tokens endpoints of the Admin API, from DuoSecurity::tokens(). Beside the endpoints' own methods, find() gives a record by id, or null.
 */
class TokensResource extends BaseResource
{
    use FindsTokens;

    /**
     * Retrieve hardware tokens, or the one with a type and serial: GET /admin/v1/tokens.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset, type, serial.
     * @return ListQuery<Token>
     */
    public function listTokens(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListTokensRequest([...$params, ...$page]), Token::class);
    }

    /**
     * Import a hardware token (h6, h8 or yk): POST /admin/v1/tokens.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: secret, counter, private_id, aes_key.
     */
    public function createToken(string $type, string $serial, array $params = []): Token
    {
        return $this->fetch(new CreateTokenRequest($type, $serial, $params));
    }

    /**
     * Retrieve a hardware token: GET /admin/v1/tokens/{token_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getToken(string $tokenId, array $params = []): Token
    {
        return $this->fetch(new GetTokenRequest($tokenId, $params));
    }

    /**
     * Resynchronize a hardware token with three codes in a row: POST /admin/v1/tokens/{token_id}/resync.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function resyncToken(string $tokenId, string $code1, string $code2, string $code3, array $params = []): bool
    {
        return $this->perform(new ResyncTokenRequest($tokenId, $code1, $code2, $code3, $params));
    }

    /**
     * Delete a hardware token (not one bought from Duo): DELETE /admin/v1/tokens/{token_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteToken(string $tokenId, array $params = []): bool
    {
        return $this->perform(new DeleteTokenRequest($tokenId, $params));
    }

    /**
     * Retrieve U2F tokens (Duo now answers 403): GET /admin/v1/u2ftokens.
     *
     * @deprecated Duo deprecated this endpoint.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     * @return Collection<int, array<string, mixed>|string>
     */
    public function listU2fTokens(array $params = []): Collection
    {
        return $this->fetch(new ListU2fTokensRequest($params));
    }
}
