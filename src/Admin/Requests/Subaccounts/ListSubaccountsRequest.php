<?php

namespace spkm\DuoSecurity\Admin\Requests\Subaccounts;

use spkm\DuoSecurity\Admin\Data\Subaccounts\Subaccount;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Retrieve the subaccounts: POST /accounts/v1/account/list. Requires the "Grant accounts - Read" permission.
 */
class ListSubaccountsRequest extends WriteRequest
{
    protected ?string $dto = Subaccount::class;

    protected string $responseShape = self::LIST;

    protected bool $safe = true;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/accounts/v1/account/list';
    }
}
