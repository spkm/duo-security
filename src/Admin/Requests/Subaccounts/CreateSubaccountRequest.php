<?php

namespace spkm\DuoSecurity\Admin\Requests\Subaccounts;

use spkm\DuoSecurity\Admin\Data\Subaccounts\Subaccount;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create a subaccount: POST /accounts/v1/account/create. Requires the "Grant accounts - Write" permission.
 */
class CreateSubaccountRequest extends WriteRequest
{
    protected ?string $dto = Subaccount::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(string $name, array $params = [])
    {
        $this->params = ['name' => $name, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/accounts/v1/account/create';
    }
}
