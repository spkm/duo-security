<?php

namespace spkm\DuoSecurity\Admin\Requests\Users;

use spkm\DuoSecurity\Admin\Data\BypassCodes\BypassCode;
use spkm\DuoSecurity\Requests\ListRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a user's bypass codes (their metadata, not the codes): GET /admin/v1/users/{user_id}/bypass_codes. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListUserBypassCodesRequest extends ListRequest
{
    protected ?string $dto = BypassCode::class;

    protected int $maxPageSize = 500;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     */
    public function __construct(protected string $userId, array $params = [])
    {
        parent::__construct($params);
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/users/'.Path::segment($this->userId).'/bypass_codes';
    }
}
