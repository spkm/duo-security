<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\AdministrativeUnits\AdministrativeUnit;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\AddAdministrativeUnitAdminRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\AddAdministrativeUnitGroupRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\AddAdministrativeUnitIntegrationRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\CreateAdministrativeUnitRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\DeleteAdministrativeUnitRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\GetAdministrativeUnitRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\ListAdministrativeUnitsRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\ModifyAdministrativeUnitRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\RemoveAdministrativeUnitAdminRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\RemoveAdministrativeUnitGroupRequest;
use spkm\DuoSecurity\Admin\Requests\AdministrativeUnits\RemoveAdministrativeUnitIntegrationRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Administrative Units endpoints of the Admin API, from DuoSecurity::administrativeUnits().
 */
class AdministrativeUnitsResource extends BaseResource
{
    /**
     * Retrieve administrative units, or those of an administrator, a group or an integration: GET /admin/v1/administrative_units.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset, admin_id, group_id, integration_key.
     * @return ListQuery<AdministrativeUnit>
     */
    public function listAdministrativeUnits(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListAdministrativeUnitsRequest([...$params, ...$page]), AdministrativeUnit::class);
    }

    /**
     * Retrieve an administrative unit: GET /admin/v1/administrative_units/{admin_unit_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getAdministrativeUnit(string $adminUnitId, array $params = []): AdministrativeUnit
    {
        return $this->fetch(new GetAdministrativeUnitRequest($adminUnitId, $params));
    }

    /**
     * Create an administrative unit: POST /admin/v1/administrative_units.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: restrict_by_groups, restrict_by_integrations, admins, groups, integrations.
     */
    public function createAdministrativeUnit(string $name, string $description, array $params = []): AdministrativeUnit
    {
        return $this->fetch(new CreateAdministrativeUnitRequest($name, $description, $params));
    }

    /**
     * Modify an administrative unit (its lists are added to): POST /admin/v1/administrative_units/{admin_unit_id}.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: name, description, restrict_by_groups, restrict_by_integrations, admins, groups, integrations.
     */
    public function modifyAdministrativeUnit(string $adminUnitId, array $params = []): AdministrativeUnit
    {
        return $this->fetch(new ModifyAdministrativeUnitRequest($adminUnitId, $params));
    }

    /**
     * Add an administrator to an administrative unit: POST /admin/v1/administrative_units/{admin_unit_id}/admin/{admin_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function addAdministrativeUnitAdmin(string $adminUnitId, string $adminId, array $params = []): AdministrativeUnit
    {
        return $this->fetch(new AddAdministrativeUnitAdminRequest($adminUnitId, $adminId, $params));
    }

    /**
     * Remove an administrator from an administrative unit: DELETE /admin/v1/administrative_units/{admin_unit_id}/admin/{admin_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function removeAdministrativeUnitAdmin(string $adminUnitId, string $adminId, array $params = []): AdministrativeUnit
    {
        return $this->fetch(new RemoveAdministrativeUnitAdminRequest($adminUnitId, $adminId, $params));
    }

    /**
     * Add a group to an administrative unit: POST /admin/v1/administrative_units/{admin_unit_id}/group/{group_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function addAdministrativeUnitGroup(string $adminUnitId, string $groupId, array $params = []): AdministrativeUnit
    {
        return $this->fetch(new AddAdministrativeUnitGroupRequest($adminUnitId, $groupId, $params));
    }

    /**
     * Remove a group from an administrative unit: DELETE /admin/v1/administrative_units/{admin_unit_id}/group/{group_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function removeAdministrativeUnitGroup(string $adminUnitId, string $groupId, array $params = []): AdministrativeUnit
    {
        return $this->fetch(new RemoveAdministrativeUnitGroupRequest($adminUnitId, $groupId, $params));
    }

    /**
     * Add an integration to an administrative unit: POST /admin/v1/administrative_units/{admin_unit_id}/integration/{integration_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function addAdministrativeUnitIntegration(string $adminUnitId, string $integrationKey, array $params = []): AdministrativeUnit
    {
        return $this->fetch(new AddAdministrativeUnitIntegrationRequest($adminUnitId, $integrationKey, $params));
    }

    /**
     * Remove an integration from an administrative unit: DELETE /admin/v1/administrative_units/{admin_unit_id}/integration/{integration_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function removeAdministrativeUnitIntegration(string $adminUnitId, string $integrationKey, array $params = []): AdministrativeUnit
    {
        return $this->fetch(new RemoveAdministrativeUnitIntegrationRequest($adminUnitId, $integrationKey, $params));
    }

    /**
     * Delete an administrative unit: DELETE /admin/v1/administrative_units/{admin_unit_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteAdministrativeUnit(string $adminUnitId, array $params = []): bool
    {
        return $this->perform(new DeleteAdministrativeUnitRequest($adminUnitId, $params));
    }
}
