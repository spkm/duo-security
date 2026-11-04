<?php

namespace spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators;

use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\DesktopAuthenticator;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve Duo Desktop authenticators: GET /admin/v1/desktop_authenticators. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListDesktopAuthenticatorsRequest extends ListRequest
{
    protected ?string $dto = DesktopAuthenticator::class;

    protected int $maxPageSize = 500;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/desktop_authenticators';
    }
}
