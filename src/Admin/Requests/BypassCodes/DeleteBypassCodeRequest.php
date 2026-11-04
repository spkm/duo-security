<?php

namespace spkm\DuoSecurity\Admin\Requests\BypassCodes;

use spkm\DuoSecurity\Requests\DeleteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Delete a bypass code: DELETE /admin/v1/bypass_codes/{bypass_code_id}. Requires the "Grant resource - Write" permission.
 */
class DeleteBypassCodeRequest extends DeleteRequest
{
    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $bypassCodeId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/bypass_codes/'.Path::segment($this->bypassCodeId);
    }
}
