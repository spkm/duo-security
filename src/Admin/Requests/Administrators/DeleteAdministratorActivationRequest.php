<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a pending administrator activation: DELETE /admin/v1/admins/activations/{admin_activation_id}. Requires the "Grant administrators - Write" permission.
 */
class DeleteAdministratorActivationRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $adminActivationId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/activations/'.Path::segment($this->adminActivationId);
    }
}
