<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Restore up to 100 users from the Trash: POST /admin/v1/users/bulk_restore. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: user_id_list.
 */
class BulkRestoreUsersRequest extends WriteRequest
{
    protected string $responseShape = self::RAW;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: user_id_list.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/bulk_restore';
    }
}
