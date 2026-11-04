<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\Users\DirectorySync;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the user directory syncs: GET /admin/v1/users/directorysync. Requires the "Grant resource - Read" permission.
 */
class ListDirectorySyncsRequest extends GetRequest
{
    protected ?string $dto = DirectorySync::class;

    protected string $responseShape = self::LIST;

    protected bool $cacheable = true;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/directorysync';
    }
}
