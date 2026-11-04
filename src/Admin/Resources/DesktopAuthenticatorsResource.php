<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\DesktopAuthenticator;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\DeleteDesktopAuthenticatorRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\GetDesktopAuthenticatorRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\ListDesktopAuthenticatorsRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Desktop Authenticators endpoints of the Admin API, from DuoSecurity::desktopAuthenticators().
 */
class DesktopAuthenticatorsResource extends BaseResource
{
    /**
     * Retrieve Duo Desktop authenticators: GET /admin/v1/desktop_authenticators.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<DesktopAuthenticator>
     */
    public function listDesktopAuthenticators(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListDesktopAuthenticatorsRequest([...$params, ...$page]), DesktopAuthenticator::class);
    }

    /**
     * Retrieve a Duo Desktop authenticator: GET /admin/v1/desktop_authenticators/{dakey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getDesktopAuthenticator(string $dakey, array $params = []): DesktopAuthenticator
    {
        return $this->fetch(new GetDesktopAuthenticatorRequest($dakey, $params));
    }

    /**
     * Delete a Duo Desktop authenticator: DELETE /admin/v1/desktop_authenticators/{dakey}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteDesktopAuthenticator(string $dakey, array $params = []): bool
    {
        return $this->perform(new DeleteDesktopAuthenticatorRequest($dakey, $params));
    }
}
