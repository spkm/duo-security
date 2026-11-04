<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\Users\DirectorySyncResult;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Synchronize one user from a directory: POST /admin/v1/users/directorysync/{directory_key}/syncuser. Requires the "Grant resource - Write" permission.
 */
class SyncUserFromDirectoryRequest extends WriteRequest
{
    protected ?string $dto = DirectorySyncResult::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $directoryKey, string $username, array $params = [])
    {
        $this->params = ['username' => $username, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/directorysync/'.Path::segment($this->directoryKey).'/syncuser';
    }
}
