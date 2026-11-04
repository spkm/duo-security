<?php

namespace spkm\DuoSecurity\Admin\Resources;

use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\Subaccounts\Subaccount;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\CreateSubaccountRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\DeleteSubaccountRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\ListSubaccountsRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Subaccounts endpoints of the Admin API, from DuoSecurity::subaccounts().
 */
class SubaccountsResource extends BaseResource
{
    /**
     * Retrieve the subaccounts: POST /accounts/v1/account/list.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     * @return Collection<int, Subaccount>
     */
    public function listSubaccounts(array $params = []): Collection
    {
        return $this->fetch(new ListSubaccountsRequest($params));
    }

    /**
     * Create a subaccount: POST /accounts/v1/account/create.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function createSubaccount(string $name, array $params = []): Subaccount
    {
        return $this->fetch(new CreateSubaccountRequest($name, $params));
    }

    /**
     * Delete a subaccount (409 when it has subaccounts of its own): POST /accounts/v1/account/delete.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteSubaccount(string $accountId, array $params = []): bool
    {
        return $this->perform(new DeleteSubaccountRequest($accountId, $params));
    }
}
