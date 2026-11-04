<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a phone: DELETE /admin/v1/phones/{phone_id}. Requires the "Grant resource - Write" permission.
 */
class DeletePhoneRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $phoneId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/phones/'.Path::segment($this->phoneId);
    }
}
