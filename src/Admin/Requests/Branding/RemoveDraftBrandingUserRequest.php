<?php

namespace spkm\DuoSecurity\Admin\Requests\Branding;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Stop showing the draft custom branding to a user: DELETE /admin/v1/branding/draft/users/{user_id}. Requires the "Grant settings" permission.
 */
class RemoveDraftBrandingUserRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $userId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/branding/draft/users/'.Path::segment($this->userId);
    }
}
