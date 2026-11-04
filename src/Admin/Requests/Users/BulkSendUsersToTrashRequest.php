<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Send up to 100 users to the Trash, for permanent deletion after seven days: POST /admin/v1/users/bulk_send_to_trash. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: user_id_list.
 */
class BulkSendUsersToTrashRequest extends WriteRequest
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
        return '/admin/v1/users/bulk_send_to_trash';
    }
}
