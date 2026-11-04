<?php

namespace spkm\DuoSecurity\Admin\Requests\Tokens;

use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Import a hardware token (h6, h8 or yk): POST /admin/v1/tokens. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: secret, counter, private_id, aes_key.
 */
class CreateTokenRequest extends WriteRequest
{
    protected ?string $dto = Token::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: secret, counter, private_id, aes_key.
     */
    public function __construct(string $type, string $serial, array $params = [])
    {
        $this->params = ['type' => $type, 'serial' => $serial, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/tokens';
    }
}
