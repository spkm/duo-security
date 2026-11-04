<?php

namespace spkm\DuoSecurity\Admin\Requests\Tokens;

use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve hardware tokens, or the one with a type and serial: GET /admin/v1/tokens. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset, type, serial.
 */
class ListTokensRequest extends ListRequest
{
    protected ?string $dto = Token::class;

    protected int $maxPageSize = 500;

    protected bool $cacheable = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/tokens';
    }
}
