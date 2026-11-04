<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\AdminSyncResult;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Synchronize one administrator from a directory: POST /admin/v1/admins/directorysync/{directory_key}/syncadmin. Requires the "Grant administrators - Write" permission.
 */
class SyncAdministratorFromDirectoryRequest extends WriteRequest
{
    protected ?string $dto = AdminSyncResult::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $directoryKey, string $email, array $params = [])
    {
        $this->params = ['email' => $email, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/directorysync/'.Path::segment($this->directoryKey).'/syncadmin';
    }
}
