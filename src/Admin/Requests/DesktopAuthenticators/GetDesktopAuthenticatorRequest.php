<?php

namespace spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators;

use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\DesktopAuthenticator;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a Duo Desktop authenticator: GET /admin/v1/desktop_authenticators/{dakey}. Requires the "Grant resource - Read" permission.
 */
class GetDesktopAuthenticatorRequest extends GetRequest
{
    protected ?string $dto = DesktopAuthenticator::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $dakey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/desktop_authenticators/'.Path::segment($this->dakey);
    }
}
