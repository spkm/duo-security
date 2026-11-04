<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Administrators\ActivationLink;
use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Admin\Data\Administrators\AdminActivation;
use spkm\DuoSecurity\Admin\Data\Administrators\AdminSyncResult;
use spkm\DuoSecurity\Admin\Data\Administrators\AllowedAuthMethods;
use spkm\DuoSecurity\Admin\Data\Administrators\PasswordManagement;
use spkm\DuoSecurity\Admin\Requests\Administrators\ClearAdministratorInactivityRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\CreateAdministratorActivationLinkRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\CreateAdministratorActivationRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\CreateAdministratorRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\DeleteAdministratorActivationLinkRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\DeleteAdministratorActivationRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\DeleteAdministratorRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\EmailAdministratorActivationLinkRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\GetAdministratorPasswordManagementRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\GetAdministratorRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\GetAllowedAdministratorAuthMethodsRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\ListAdministratorActivationsRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\ListAdministratorPasswordManagementRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\ListAdministratorsRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\ModifyAdministratorRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\ResetAdministratorAuthAttemptsRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\SetAdministratorPasswordManagementRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\SetAllowedAdministratorAuthMethodsRequest;
use spkm\DuoSecurity\Admin\Requests\Administrators\SyncAdministratorFromDirectoryRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Administrators endpoints of the Admin API, from DuoSecurity::administrators().
 */
class AdministratorsResource extends BaseResource
{
    /**
     * Retrieve administrators: GET /admin/v1/admins.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<Admin>
     */
    public function listAdministrators(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListAdministratorsRequest([...$params, ...$page]), Admin::class);
    }

    /**
     * Create an administrator, pending activation: POST /admin/v1/admins.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: phone, role, role_id, restricted_by_admin_units, send_email, subaccount_role, subaccount_role_id, token_id, valid_days.
     */
    public function createAdministrator(string $email, string $name, array $params = []): Admin
    {
        return $this->fetch(new CreateAdministratorRequest($email, $name, $params));
    }

    /**
     * Retrieve an administrator: GET /admin/v1/admins/{admin_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getAdministrator(string $adminId, array $params = []): Admin
    {
        return $this->fetch(new GetAdministratorRequest($adminId, $params));
    }

    /**
     * Modify an administrator: POST /admin/v1/admins/{admin_id}.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: name, phone, password_change_required, role, role_id, restricted_by_admin_units, status, subaccount_role, subaccount_role_id, token_id.
     */
    public function modifyAdministrator(string $adminId, array $params = []): Admin
    {
        return $this->fetch(new ModifyAdministratorRequest($adminId, $params));
    }

    /**
     * Delete an administrator (not one synced from a directory): DELETE /admin/v1/admins/{admin_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteAdministrator(string $adminId, array $params = []): bool
    {
        return $this->perform(new DeleteAdministratorRequest($adminId, $params));
    }

    /**
     * Clear an administrator's failed login attempts: POST /admin/v1/admins/{admin_id}/reset.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function resetAdministratorAuthAttempts(string $adminId, array $params = []): bool
    {
        return $this->perform(new ResetAdministratorAuthAttemptsRequest($adminId, $params));
    }

    /**
     * Clear an administrator's Expired status: POST /admin/v1/admins/{admin_id}/clear_inactivity.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function clearAdministratorInactivity(string $adminId, array $params = []): bool
    {
        return $this->perform(new ClearAdministratorInactivityRequest($adminId, $params));
    }

    /**
     * Create an activation link for a pending administrator: POST /admin/v1/admins/{admin_id}/activation_link.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function createAdministratorActivationLink(string $adminId, array $params = []): Admin
    {
        return $this->fetch(new CreateAdministratorActivationLinkRequest($adminId, $params));
    }

    /**
     * Email a pending administrator their activation link: POST /admin/v1/admins/{admin_id}/activation_link/email.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function emailAdministratorActivationLink(string $adminId, array $params = []): ActivationLink
    {
        return $this->fetch(new EmailAdministratorActivationLinkRequest($adminId, $params));
    }

    /**
     * Delete a pending administrator's activation link: DELETE /admin/v1/admins/{admin_id}/activation_link.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteAdministratorActivationLink(string $adminId, array $params = []): Admin
    {
        return $this->fetch(new DeleteAdministratorActivationLinkRequest($adminId, $params));
    }

    /**
     * Create an activation link for a new administrator: POST /admin/v1/admins/activations.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: admin_name, admin_role, admin_role_id, send_email, valid_days.
     */
    public function createAdministratorActivation(string $email, array $params = []): AdminActivation
    {
        return $this->fetch(new CreateAdministratorActivationRequest($email, $params));
    }

    /**
     * Retrieve pending administrator activations: GET /admin/v1/admins/activations.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<AdminActivation>
     */
    public function listAdministratorActivations(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListAdministratorActivationsRequest([...$params, ...$page]), AdminActivation::class);
    }

    /**
     * Delete a pending administrator activation: DELETE /admin/v1/admins/activations/{admin_activation_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteAdministratorActivation(string $adminActivationId, array $params = []): bool
    {
        return $this->perform(new DeleteAdministratorActivationRequest($adminActivationId, $params));
    }

    /**
     * Synchronize one administrator from a directory: POST /admin/v1/admins/directorysync/{directory_key}/syncadmin.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function syncAdministratorFromDirectory(string $directoryKey, string $email, array $params = []): AdminSyncResult
    {
        return $this->fetch(new SyncAdministratorFromDirectoryRequest($directoryKey, $email, $params));
    }

    /**
     * Retrieve every administrator's external password management status: GET /admin/v1/admins/password_mgmt.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<PasswordManagement>
     */
    public function listAdministratorPasswordManagement(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListAdministratorPasswordManagementRequest([...$params, ...$page]), PasswordManagement::class);
    }

    /**
     * Retrieve an administrator's external password management status: GET /admin/v1/admins/{admin_id}/password_mgmt.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getAdministratorPasswordManagement(string $adminId, array $params = []): PasswordManagement
    {
        return $this->fetch(new GetAdministratorPasswordManagementRequest($adminId, $params));
    }

    /**
     * Set an administrator's external password management, and their password: POST /admin/v1/admins/{admin_id}/password_mgmt.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: has_external_password_mgmt, password.
     */
    public function setAdministratorPasswordManagement(string $adminId, array $params = []): PasswordManagement
    {
        return $this->fetch(new SetAdministratorPasswordManagementRequest($adminId, $params));
    }

    /**
     * Retrieve the factors administrators may log in to the Admin Panel with: GET /admin/v1/admins/allowed_auth_methods.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getAllowedAdministratorAuthMethods(array $params = []): AllowedAuthMethods
    {
        return $this->fetch(new GetAllowedAdministratorAuthMethodsRequest($params));
    }

    /**
     * Set the factors administrators may log in to the Admin Panel with: POST /admin/v1/admins/allowed_auth_methods.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: hardware_token_enabled, mobile_otp_enabled, push_enabled, sms_enabled, verified_push_enabled, verified_push_length, voice_enabled, webauthn_enabled, yubikey_enabled.
     */
    public function setAllowedAdministratorAuthMethods(array $params = []): AllowedAuthMethods
    {
        return $this->fetch(new SetAllowedAdministratorAuthMethodsRequest($params));
    }
}
