<?php

namespace spkm\DuoSecurity\Admin\Requests\BypassCodes;

use spkm\DuoSecurity\Admin\Data\BypassCodes\BypassCode;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a bypass code's metadata: GET /admin/v1/bypass_codes/{bypass_code_id}. Requires the "Grant resource - Read" permission.
 */
class GetBypassCodeRequest extends GetRequest
{
    protected ?string $dto = BypassCode::class;

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
