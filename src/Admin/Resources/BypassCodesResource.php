<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\BypassCodes\BypassCode;
use spkm\DuoSecurity\Admin\Requests\BypassCodes\DeleteBypassCodeRequest;
use spkm\DuoSecurity\Admin\Requests\BypassCodes\GetBypassCodeRequest;
use spkm\DuoSecurity\Admin\Requests\BypassCodes\ListBypassCodesRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Bypass Codes endpoints of the Admin API, from DuoSecurity::bypassCodes().
 */
class BypassCodesResource extends BaseResource
{
    /**
     * Retrieve every bypass code's metadata (not the codes): GET /admin/v1/bypass_codes.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<BypassCode>
     */
    public function listBypassCodes(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListBypassCodesRequest([...$params, ...$page]), BypassCode::class);
    }

    /**
     * Retrieve a bypass code's metadata: GET /admin/v1/bypass_codes/{bypass_code_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getBypassCode(string $bypassCodeId, array $params = []): BypassCode
    {
        return $this->fetch(new GetBypassCodeRequest($bypassCodeId, $params));
    }

    /**
     * Delete a bypass code: DELETE /admin/v1/bypass_codes/{bypass_code_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteBypassCode(string $bypassCodeId, array $params = []): bool
    {
        return $this->perform(new DeleteBypassCodeRequest($bypassCodeId, $params));
    }
}
