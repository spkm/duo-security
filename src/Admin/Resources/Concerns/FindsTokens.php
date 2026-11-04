<?php

namespace spkm\DuoSecurity\Admin\Resources\Concerns;

use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Admin\Requests\Tokens\GetTokenRequest;

/**
 * Finders for hardware tokens, beside the endpoints' own methods: by id (null when there is none) and by type and
 * serial, which Duo matches exactly, one token at most.
 */
trait FindsTokens
{
    /**
     * The token with this id, or null when Duo has none.
     */
    public function find(string $tokenId): ?Token
    {
        return $this->fetchOrNull(new GetTokenRequest($tokenId));
    }

    /**
     * The token with this type (h6, h8, yk or d1) and serial, or null when Duo has none.
     */
    public function findBySerial(string $type, string $serial): ?Token
    {
        return $this->listTokens(['type' => $type, 'serial' => $serial])->first();
    }
}
