<?php

namespace spkm\DuoSecurity\Admin\Resources;

/**
 * A resource for every section of the Admin API's documentation, on the connector and so on the DuoSecurity facade.
 */
trait HasResources
{
    /**
     * The Admin Roles endpoints of the Admin API.
     */
    public function adminRoles(): AdminRolesResource
    {
        return new AdminRolesResource($this);
    }

    /**
     * The Administrative Units endpoints of the Admin API.
     */
    public function administrativeUnits(): AdministrativeUnitsResource
    {
        return new AdministrativeUnitsResource($this);
    }

    /**
     * The Administrators endpoints of the Admin API.
     */
    public function administrators(): AdministratorsResource
    {
        return new AdministratorsResource($this);
    }

    /**
     * The Subaccount Billing endpoints of the Admin API.
     */
    public function billing(): BillingResource
    {
        return new BillingResource($this);
    }

    /**
     * The Custom Branding endpoints of the Admin API.
     */
    public function branding(): BrandingResource
    {
        return new BrandingResource($this);
    }

    /**
     * The Bulk Operations endpoints of the Admin API.
     */
    public function bulk(): BulkResource
    {
        return new BulkResource($this);
    }

    /**
     * The Bypass Codes endpoints of the Admin API.
     */
    public function bypassCodes(): BypassCodesResource
    {
        return new BypassCodesResource($this);
    }

    /**
     * The Desktop Authenticators endpoints of the Admin API.
     */
    public function desktopAuthenticators(): DesktopAuthenticatorsResource
    {
        return new DesktopAuthenticatorsResource($this);
    }

    /**
     * The Endpoints endpoints of the Admin API.
     */
    public function endpoints(): EndpointsResource
    {
        return new EndpointsResource($this);
    }

    /**
     * The Groups endpoints of the Admin API.
     */
    public function groups(): GroupsResource
    {
        return new GroupsResource($this);
    }

    /**
     * The Identity Verification endpoints of the Admin API.
     */
    public function identityVerification(): IdentityVerificationResource
    {
        return new IdentityVerificationResource($this);
    }

    /**
     * The Account Info endpoints of the Admin API.
     */
    public function info(): InfoResource
    {
        return new InfoResource($this);
    }

    /**
     * The Integrations endpoints of the Admin API.
     */
    public function integrations(): IntegrationsResource
    {
        return new IntegrationsResource($this);
    }

    /**
     * The Logs endpoints of the Admin API.
     */
    public function logs(): LogsResource
    {
        return new LogsResource($this);
    }

    /**
     * The Passport endpoints of the Admin API.
     */
    public function passport(): PassportResource
    {
        return new PassportResource($this);
    }

    /**
     * The Phones endpoints of the Admin API.
     */
    public function phones(): PhonesResource
    {
        return new PhonesResource($this);
    }

    /**
     * The Policies endpoints of the Admin API.
     */
    public function policies(): PoliciesResource
    {
        return new PoliciesResource($this);
    }

    /**
     * The Registered Devices endpoints of the Admin API.
     */
    public function registeredDevices(): RegisteredDevicesResource
    {
        return new RegisteredDevicesResource($this);
    }

    /**
     * The Settings endpoints of the Admin API.
     */
    public function settings(): SettingsResource
    {
        return new SettingsResource($this);
    }

    /**
     * The Shared Device Authentication endpoints of the Admin API.
     */
    public function sharedDeviceAuth(): SharedDeviceAuthResource
    {
        return new SharedDeviceAuthResource($this);
    }

    /**
     * The Subaccounts endpoints of the Admin API.
     */
    public function subaccounts(): SubaccountsResource
    {
        return new SubaccountsResource($this);
    }

    /**
     * The Hardware Tokens endpoints of the Admin API.
     */
    public function tokens(): TokensResource
    {
        return new TokensResource($this);
    }

    /**
     * The Trust Monitor endpoints of the Admin API.
     */
    public function trustMonitor(): TrustMonitorResource
    {
        return new TrustMonitorResource($this);
    }

    /**
     * The Users endpoints of the Admin API.
     */
    public function users(): UsersResource
    {
        return new UsersResource($this);
    }

    /**
     * The WebAuthn Credentials endpoints of the Admin API.
     */
    public function webAuthnCredentials(): WebAuthnCredentialsResource
    {
        return new WebAuthnCredentialsResource($this);
    }
}
