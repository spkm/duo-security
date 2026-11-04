<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve phones, or the one with a number: GET /admin/v1/phones. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset, number, extension.
 */
class ListPhonesRequest extends ListRequest
{
    protected ?string $dto = Phone::class;

    protected int $maxPageSize = 500;

    protected bool $cacheable = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/phones';
    }
}
