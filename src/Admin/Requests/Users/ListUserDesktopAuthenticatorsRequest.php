<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\DesktopAuthenticator;
use spkm\DuoSecurity\Requests\ListRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a user's Duo Desktop authenticators: GET /admin/v1/users/{user_id}/desktopauthenticators. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListUserDesktopAuthenticatorsRequest extends ListRequest
{
    protected ?string $dto = DesktopAuthenticator::class;

    protected int $maxPageSize = 500;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     */
    public function __construct(protected string $userId, array $params = [])
    {
        parent::__construct($params);
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/desktopauthenticators';
    }
}
