# Endpoints

Every call the package makes to Duo: all 184 operations of the Admin API (https://duo.com/docs/adminapi) and the Auth API (https://duo.com/docs/authapi), as their documentation described them on 2026-11-04.

Admin API resources are reached from the facade, `DuoSecurity::{resource}()`; the Auth API from `DuoSecurity::auth()`. Each resource has a method for every request in its section, named as the request is (without `Request`) and taking the same arguments: a path parameter each, then an array of the parameters Duo documents. A list's method gives a `ListQuery` (`get()` for a page, `all()` or `lazy()` for every record), a log's a `LogQuery` (`between()` or `since()`, then `get()`, `all()` or `lazy()`). Requests are plain Saloon classes under `spkm\DuoSecurity\Admin\Requests\{Section}` and `spkm\DuoSecurity\Auth\Requests`, for `DuoSecurity::send()`. Endpoints Duo has deprecated are marked so, and their classes carry `@deprecated`.

## Contents

### Admin API

- [`subaccounts()`](#subaccounts) (3)
- [`billing()`](#billing) (6)
- [`users()`](#users) (27)
- [`bulk()`](#bulk) (1)
- [`groups()`](#groups) (6)
- [`identityVerification()`](#identity-verification) (3)
- [`phones()`](#phones) (10)
- [`tokens()`](#tokens) (6)
- [`webAuthnCredentials()`](#web-authn-credentials) (3)
- [`desktopAuthenticators()`](#desktop-authenticators) (3)
- [`sharedDeviceAuth()`](#shared-device-auth) (5)
- [`bypassCodes()`](#bypass-codes) (3)
- [`integrations()`](#integrations) (19)
- [`policies()`](#policies) (10)
- [`endpoints()`](#endpoints) (2)
- [`registeredDevices()`](#registered-devices) (9)
- [`passport()`](#passport) (2)
- [`administrators()`](#administrators) (19)
- [`adminRoles()`](#admin-roles) (2)
- [`administrativeUnits()`](#administrative-units) (11)
- [`logs()`](#logs) (6)
- [`trustMonitor()`](#trust-monitor) (1)
- [`settings()`](#settings) (5)
- [`branding()`](#branding) (9)
- [`info()`](#info) (5)

### Auth API

- [`auth()`](#auth) (8)

## Admin API

### Subaccounts

`DuoSecurity::subaccounts()`, the Subaccounts section.

| Method | Path | Request | Returns |
|---|---|---|---|
| POST | `/accounts/v1/account/list` | `ListSubaccountsRequest` | Collection<Subaccount> |
| POST | `/accounts/v1/account/create` | `CreateSubaccountRequest` | Subaccount |
| POST | `/accounts/v1/account/delete` | `DeleteSubaccountRequest` | bool |

### Billing

`DuoSecurity::billing()`, the Subaccounts section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/billing/edition` | `GetEditionRequest` | Edition |
| POST | `/admin/v1/billing/edition` | `SetEditionRequest` | bool |
| GET | `/admin/v1/billing/telephony_credits` | `GetTelephonyCreditsRequest` | TelephonyCredits |
| POST | `/admin/v1/billing/telephony_credits` | `SetTelephonyCreditsRequest` | TelephonyCredits |
| GET | `/admin/v1/billing/user_limit` | `GetUserLimitRequest` | UserLimit |
| POST | `/admin/v1/billing/user_limit` | `SetUserLimitRequest` | bool |

### Users

`DuoSecurity::users()`, the Users section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/users` | `ListUsersRequest` | ListQuery<User> |
| POST | `/admin/v1/users` | `CreateUserRequest` | User |
| POST | `/admin/v1/users/bulk_create` | `BulkCreateUsersRequest` | Collection<User> |
| POST | `/admin/v1/users/bulk_restore` | `BulkRestoreUsersRequest` | mixed |
| POST | `/admin/v1/users/bulk_send_to_trash` | `BulkSendUsersToTrashRequest` | mixed |
| GET | `/admin/v1/users/{user_id}` | `GetUserRequest` | User |
| POST | `/admin/v1/users/{user_id}` | `ModifyUserRequest` | User |
| DELETE | `/admin/v1/users/{user_id}` | `DeleteUserRequest` | bool |
| POST | `/admin/v1/users/enroll` | `EnrollUserRequest` | mixed |
| GET | `/admin/v1/users/{user_id}/bypass_codes` | `ListUserBypassCodesRequest` | ListQuery<BypassCode> |
| POST | `/admin/v1/users/{user_id}/bypass_codes` | `CreateUserBypassCodesRequest` | Collection |
| GET | `/admin/v1/users/{user_id}/groups` | `ListUserGroupsRequest` | ListQuery<Group> |
| POST | `/admin/v1/users/{user_id}/groups` | `AddUserToGroupRequest` | bool |
| DELETE | `/admin/v1/users/{user_id}/groups/{group_id}` | `RemoveUserFromGroupRequest` | bool |
| GET | `/admin/v1/users/{user_id}/phones` | `ListUserPhonesRequest` | ListQuery<Phone> |
| POST | `/admin/v1/users/{user_id}/phones` | `AddUserPhoneRequest` | bool |
| DELETE | `/admin/v1/users/{user_id}/phones/{phone_id}` | `RemoveUserPhoneRequest` | bool |
| GET | `/admin/v1/users/{user_id}/tokens` | `ListUserTokensRequest` | ListQuery<Token> |
| POST | `/admin/v1/users/{user_id}/tokens` | `AddUserTokenRequest` | bool |
| DELETE | `/admin/v1/users/{user_id}/tokens/{token_id}` | `RemoveUserTokenRequest` | bool |
| GET | `/admin/v1/users/{user_id}/u2ftokens` | `ListUserU2fTokensRequest` | Collection (deprecated) |
| GET | `/admin/v1/users/{user_id}/webauthncredentials` | `ListUserWebAuthnCredentialsRequest` | Collection<WebAuthnCredential> |
| GET | `/admin/v1/users/{user_id}/desktopauthenticators` | `ListUserDesktopAuthenticatorsRequest` | ListQuery<DesktopAuthenticator> |
| GET | `/admin/v1/users/directorysync` | `ListDirectorySyncsRequest` | Collection<DirectorySync> |
| POST | `/admin/v1/users/directorysync/{directory_key}/syncuser` | `SyncUserFromDirectoryRequest` | DirectorySyncResult |
| POST | `/admin/v1/users/{user_id}/send_verification_push` | `SendUserVerificationPushRequest` | VerificationPush |
| GET | `/admin/v1/users/{user_id}/verification_push_response` | `GetUserVerificationPushResponseRequest` | VerificationPushResponse |

### Bulk

`DuoSecurity::bulk()`, the Bulk Operations section.

| Method | Path | Request | Returns |
|---|---|---|---|
| POST | `/admin/v1/bulk` | `RunBulkOperationsRequest` | Collection<BulkOperationResult> |

### Groups

`DuoSecurity::groups()`, the Groups section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/groups` | `ListGroupsRequest` | ListQuery<Group> |
| POST | `/admin/v1/groups` | `CreateGroupRequest` | Group |
| GET | `/admin/v2/groups/{group_id}` | `GetGroupRequest` | Group |
| GET | `/admin/v2/groups/{group_id}/users` | `ListGroupMembersRequest` | ListQuery<GroupMember> |
| POST | `/admin/v1/groups/{group_id}` | `ModifyGroupRequest` | Group |
| DELETE | `/admin/v1/groups/{group_id}` | `DeleteGroupRequest` | bool |

### Identity Verification

`DuoSecurity::identityVerification()`, the Identity Verification section.

| Method | Path | Request | Returns |
|---|---|---|---|
| POST | `/admin/v2/identity_verification/{user_id}/start` | `StartIdentityVerificationRequest` | IdentityVerification |
| GET | `/admin/v2/identity_verification/{user_id}/status` | `GetIdentityVerificationStatusRequest` | IdentityVerification |
| POST | `/admin/v2/identity_verification/{user_id}/cancel` | `CancelIdentityVerificationRequest` | bool |

### Phones

`DuoSecurity::phones()`, the Phones section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/phones` | `ListPhonesRequest` | ListQuery<Phone> |
| POST | `/admin/v1/phones` | `CreatePhoneRequest` | Phone |
| GET | `/admin/v1/phones/{phone_id}` | `GetPhoneRequest` | Phone |
| POST | `/admin/v1/phones/{phone_id}` | `ModifyPhoneRequest` | Phone |
| POST | `/admin/v2/phones/{phone_id}/quarantine_status` | `SetPhoneQuarantineStatusRequest` | bool |
| DELETE | `/admin/v1/phones/{phone_id}` | `DeletePhoneRequest` | bool |
| POST | `/admin/v1/phones/{phone_id}/activation_url` | `CreatePhoneActivationUrlRequest` | ActivationUrl |
| POST | `/admin/v1/phones/{phone_id}/send_sms_activation` | `SendPhoneSmsActivationRequest` | SmsActivation |
| POST | `/admin/v1/phones/{phone_id}/send_sms_installation` | `SendPhoneSmsInstallationRequest` | SmsInstallation |
| POST | `/admin/v1/phones/{phone_id}/send_sms_passcodes` | `SendPhoneSmsPasscodesRequest` | bool |

### Tokens

`DuoSecurity::tokens()`, the Tokens section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/tokens` | `ListTokensRequest` | ListQuery<Token> |
| POST | `/admin/v1/tokens` | `CreateTokenRequest` | Token |
| GET | `/admin/v1/tokens/{token_id}` | `GetTokenRequest` | Token |
| POST | `/admin/v1/tokens/{token_id}/resync` | `ResyncTokenRequest` | bool |
| DELETE | `/admin/v1/tokens/{token_id}` | `DeleteTokenRequest` | bool |
| GET | `/admin/v1/u2ftokens` | `ListU2fTokensRequest` | Collection (deprecated) |

### Web Authn Credentials

`DuoSecurity::webAuthnCredentials()`, the WebAuthn Credentials section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/webauthncredentials` | `ListWebAuthnCredentialsRequest` | ListQuery<WebAuthnCredential> |
| GET | `/admin/v1/webauthncredentials/{webauthnkey}` | `GetWebAuthnCredentialRequest` | WebAuthnCredential |
| DELETE | `/admin/v1/webauthncredentials/{webauthnkey}` | `DeleteWebAuthnCredentialRequest` | bool |

### Desktop Authenticators

`DuoSecurity::desktopAuthenticators()`, the Desktop Authenticators section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/desktop_authenticators` | `ListDesktopAuthenticatorsRequest` | ListQuery<DesktopAuthenticator> |
| GET | `/admin/v1/desktop_authenticators/{dakey}` | `GetDesktopAuthenticatorRequest` | DesktopAuthenticator |
| DELETE | `/admin/v1/desktop_authenticators/{dakey}` | `DeleteDesktopAuthenticatorRequest` | bool |

### Shared Device Auth

`DuoSecurity::sharedDeviceAuth()`, the Desktop Authenticators section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/desktop_authenticators/shared_device_auth` | `ListSharedDeviceAuthConfigurationsRequest` | ListQuery<SharedDeviceAuth> |
| GET | `/admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}` | `GetSharedDeviceAuthConfigurationRequest` | SharedDeviceAuth |
| POST | `/admin/v1/desktop_authenticators/shared_device_auth` | `CreateSharedDeviceAuthConfigurationRequest` | SharedDeviceAuth |
| PUT | `/admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}` | `UpdateSharedDeviceAuthConfigurationRequest` | SharedDeviceAuth |
| DELETE | `/admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}` | `DeleteSharedDeviceAuthConfigurationRequest` | bool |

### Bypass Codes

`DuoSecurity::bypassCodes()`, the Bypass Codes section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/bypass_codes` | `ListBypassCodesRequest` | ListQuery<BypassCode> |
| GET | `/admin/v1/bypass_codes/{bypass_code_id}` | `GetBypassCodeRequest` | BypassCode |
| DELETE | `/admin/v1/bypass_codes/{bypass_code_id}` | `DeleteBypassCodeRequest` | bool |

### Integrations

`DuoSecurity::integrations()`, the Integrations section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v3/integrations` | `ListIntegrationsRequest` | ListQuery<Integration> |
| POST | `/admin/v3/integrations` | `CreateIntegrationRequest` | Integration |
| GET | `/admin/v3/integrations/{integration_key}` | `GetIntegrationRequest` | Integration |
| POST | `/admin/v3/integrations/{integration_key}` | `ModifyIntegrationRequest` | Integration |
| DELETE | `/admin/v3/integrations/{integration_key}` | `DeleteIntegrationRequest` | bool |
| GET | `/admin/v1/integrations/{integration_key}/skey` | `GetIntegrationSecretKeyRequest` | SecretKey |
| GET | `/admin/v3/integrations/oauth_cc/{integration_key}/client_secret/{client_id}` | `GetOAuthClientSecretRequest` | ClientSecret |
| POST | `/admin/v3/integrations/oauth_cc/{integration_key}/client_secret/{client_id}` | `ResetOAuthClientSecretRequest` | bool |
| GET | `/admin/v3/integrations/oidc/{integration_key}/client_secret` | `GetOidcClientSecretRequest` | ClientSecret |
| POST | `/admin/v3/integrations/oidc/{integration_key}/client_secret` | `ResetOidcClientSecretRequest` | bool |
| GET | `/admin/v2/integrations` | `ListIntegrationsV2Request` | ListQuery<Integration> |
| POST | `/admin/v2/integrations` | `CreateIntegrationV2Request` | Integration |
| GET | `/admin/v2/integrations/{integration_key}` | `GetIntegrationV2Request` | Integration |
| POST | `/admin/v2/integrations/{integration_key}` | `ModifyIntegrationV2Request` | Integration |
| DELETE | `/admin/v2/integrations/{integration_key}` | `DeleteIntegrationV2Request` | bool |
| GET | `/admin/v2/integrations/oauth_cc/{integration_key}/client_secret/{client_id}` | `GetOAuthClientSecretV2Request` | ClientSecret |
| POST | `/admin/v2/integrations/oauth_cc/{integration_key}/client_secret/{client_id}` | `ResetOAuthClientSecretV2Request` | bool |
| GET | `/admin/v2/integrations/oidc/{integration_key}/client_secret` | `GetOidcClientSecretV2Request` | ClientSecret |
| POST | `/admin/v2/integrations/oidc/{integration_key}/client_secret` | `ResetOidcClientSecretV2Request` | bool |

### Policies

`DuoSecurity::policies()`, the Policies section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v2/policies/summary` | `GetPoliciesSummaryRequest` | PolicySummary |
| GET | `/admin/v2/policies` | `ListPoliciesRequest` | ListQuery<Policy> |
| GET | `/admin/v2/policies/{policy_key}` | `GetPolicyRequest` | Policy |
| GET | `/admin/v2/policies/global` | `GetGlobalPolicyRequest` | Policy |
| GET | `/admin/v2/policies/calculate` | `CalculatePolicyRequest` | PolicyCalculation |
| POST | `/admin/v2/policies/copy` | `CopyPolicyRequest` | PolicyBatch |
| POST | `/admin/v2/policies` | `CreatePolicyRequest` | Policy |
| PUT | `/admin/v2/policies/update` | `UpdatePoliciesRequest` | PolicyBatch |
| PUT | `/admin/v2/policies/{policy_key}` | `UpdatePolicyRequest` | Policy |
| DELETE | `/admin/v2/policies/{policy_key}` | `DeletePolicyRequest` | bool |

### Endpoints

`DuoSecurity::endpoints()`, the Endpoints section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/endpoints` | `ListEndpointsRequest` | ListQuery<Endpoint> |
| GET | `/admin/v1/endpoints/{epkey}` | `GetEndpointRequest` | Endpoint |

### Registered Devices

`DuoSecurity::registeredDevices()`, the Registered Devices section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/registered_devices` | `ListRegisteredDevicesRequest` | ListQuery<RegisteredDevice> |
| GET | `/admin/v1/registered_devices/{compkey}` | `GetRegisteredDeviceRequest` | RegisteredDevice |
| DELETE | `/admin/v1/registered_devices/{compkey}` | `DeleteRegisteredDeviceRequest` | bool |
| GET | `/admin/v1/registered_devices/blocked` | `ListBlockedRegisteredDevicesRequest` | ListQuery<BlockedDevice> |
| GET | `/admin/v1/registered_devices/blocked/{compkey}` | `GetBlockedRegisteredDeviceRequest` | BlockedDevice |
| POST | `/admin/v1/registered_devices/blocked` | `BlockRegisteredDevicesRequest` | Collection<BlockedDevice> |
| POST | `/admin/v1/registered_devices/blocked/{compkey}` | `BlockRegisteredDeviceRequest` | BlockedDevice |
| DELETE | `/admin/v1/registered_devices/blocked` | `UnblockRegisteredDevicesRequest` | bool |
| DELETE | `/admin/v1/registered_devices/blocked/{compkey}` | `UnblockRegisteredDeviceRequest` | bool |

### Passport

`DuoSecurity::passport()`, the Passport section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v2/passport/config` | `GetPassportConfigurationRequest` | PassportConfiguration |
| POST | `/admin/v2/passport/config` | `ModifyPassportConfigurationRequest` | bool |

### Administrators

`DuoSecurity::administrators()`, the Administrators section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/admins` | `ListAdministratorsRequest` | ListQuery<Admin> |
| POST | `/admin/v1/admins` | `CreateAdministratorRequest` | Admin |
| GET | `/admin/v1/admins/{admin_id}` | `GetAdministratorRequest` | Admin |
| POST | `/admin/v1/admins/{admin_id}` | `ModifyAdministratorRequest` | Admin |
| DELETE | `/admin/v1/admins/{admin_id}` | `DeleteAdministratorRequest` | bool |
| POST | `/admin/v1/admins/{admin_id}/reset` | `ResetAdministratorAuthAttemptsRequest` | bool |
| POST | `/admin/v1/admins/{admin_id}/clear_inactivity` | `ClearAdministratorInactivityRequest` | bool |
| POST | `/admin/v1/admins/{admin_id}/activation_link` | `CreateAdministratorActivationLinkRequest` | Admin |
| POST | `/admin/v1/admins/{admin_id}/activation_link/email` | `EmailAdministratorActivationLinkRequest` | ActivationLink |
| DELETE | `/admin/v1/admins/{admin_id}/activation_link` | `DeleteAdministratorActivationLinkRequest` | Admin |
| POST | `/admin/v1/admins/activations` | `CreateAdministratorActivationRequest` | AdminActivation |
| GET | `/admin/v1/admins/activations` | `ListAdministratorActivationsRequest` | ListQuery<AdminActivation> |
| DELETE | `/admin/v1/admins/activations/{admin_activation_id}` | `DeleteAdministratorActivationRequest` | bool |
| POST | `/admin/v1/admins/directorysync/{directory_key}/syncadmin` | `SyncAdministratorFromDirectoryRequest` | AdminSyncResult |
| GET | `/admin/v1/admins/password_mgmt` | `ListAdministratorPasswordManagementRequest` | ListQuery<PasswordManagement> |
| GET | `/admin/v1/admins/{admin_id}/password_mgmt` | `GetAdministratorPasswordManagementRequest` | PasswordManagement |
| POST | `/admin/v1/admins/{admin_id}/password_mgmt` | `SetAdministratorPasswordManagementRequest` | PasswordManagement |
| GET | `/admin/v1/admins/allowed_auth_methods` | `GetAllowedAdministratorAuthMethodsRequest` | AllowedAuthMethods |
| POST | `/admin/v1/admins/allowed_auth_methods` | `SetAllowedAdministratorAuthMethodsRequest` | AllowedAuthMethods |

### Admin Roles

`DuoSecurity::adminRoles()`, the Admin Roles section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/admin_roles` | `ListAdminRolesRequest` | Collection<AdminRole> |
| GET | `/admin/v1/admin_roles/{role_id}` | `GetAdminRoleRequest` | AdminRole |

### Administrative Units

`DuoSecurity::administrativeUnits()`, the Administrative Units section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/administrative_units` | `ListAdministrativeUnitsRequest` | ListQuery<AdministrativeUnit> |
| GET | `/admin/v1/administrative_units/{admin_unit_id}` | `GetAdministrativeUnitRequest` | AdministrativeUnit |
| POST | `/admin/v1/administrative_units` | `CreateAdministrativeUnitRequest` | AdministrativeUnit |
| POST | `/admin/v1/administrative_units/{admin_unit_id}` | `ModifyAdministrativeUnitRequest` | AdministrativeUnit |
| POST | `/admin/v1/administrative_units/{admin_unit_id}/admin/{admin_id}` | `AddAdministrativeUnitAdminRequest` | AdministrativeUnit |
| DELETE | `/admin/v1/administrative_units/{admin_unit_id}/admin/{admin_id}` | `RemoveAdministrativeUnitAdminRequest` | AdministrativeUnit |
| POST | `/admin/v1/administrative_units/{admin_unit_id}/group/{group_id}` | `AddAdministrativeUnitGroupRequest` | AdministrativeUnit |
| DELETE | `/admin/v1/administrative_units/{admin_unit_id}/group/{group_id}` | `RemoveAdministrativeUnitGroupRequest` | AdministrativeUnit |
| POST | `/admin/v1/administrative_units/{admin_unit_id}/integration/{integration_key}` | `AddAdministrativeUnitIntegrationRequest` | AdministrativeUnit |
| DELETE | `/admin/v1/administrative_units/{admin_unit_id}/integration/{integration_key}` | `RemoveAdministrativeUnitIntegrationRequest` | AdministrativeUnit |
| DELETE | `/admin/v1/administrative_units/{admin_unit_id}` | `DeleteAdministrativeUnitRequest` | bool |

### Logs

`DuoSecurity::logs()`, the Logs section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v2/logs/authentication` | `GetAuthenticationLogsRequest` | LogQuery<AuthenticationLogEvent> |
| GET | `/admin/v2/logs/authorization` | `GetAuthorizationLogsRequest` | LogQuery<AuthorizationLogEvent> |
| GET | `/admin/v2/logs/activity` | `GetActivityLogsRequest` | LogQuery<ActivityLogEvent> |
| GET | `/admin/v2/logs/telephony` | `GetTelephonyLogsRequest` | LogQuery<TelephonyLogEvent> |
| GET | `/admin/v1/logs/administrator` | `GetAdministratorLogsRequest` | LogQuery<AdministratorLogEvent> (deprecated) |
| GET | `/admin/v1/logs/offline_enrollment` | `GetOfflineEnrollmentLogsRequest` | LogQuery<OfflineEnrollmentEvent> |

### Trust Monitor

`DuoSecurity::trustMonitor()`, the Trust Monitor section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/trust_monitor/events` | `ListTrustMonitorEventsRequest` | ListQuery<TrustMonitorEvent> |

### Settings

`DuoSecurity::settings()`, the Settings section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/settings` | `GetSettingsRequest` | Settings |
| POST | `/admin/v1/settings` | `ModifySettingsRequest` | Settings |
| GET | `/admin/v1/logo` | `GetLogoRequest` | string |
| POST | `/admin/v1/logo` | `ModifyLogoRequest` | bool |
| DELETE | `/admin/v1/logo` | `DeleteLogoRequest` | bool |

### Branding

`DuoSecurity::branding()`, the Custom Branding section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/branding` | `GetBrandingRequest` | Branding |
| POST | `/admin/v1/branding` | `ModifyBrandingRequest` | Branding |
| GET | `/admin/v1/branding/draft` | `GetDraftBrandingRequest` | Branding |
| POST | `/admin/v1/branding/draft` | `ModifyDraftBrandingRequest` | Branding |
| POST | `/admin/v1/branding/draft/users/{user_id}` | `AddDraftBrandingUserRequest` | bool |
| DELETE | `/admin/v1/branding/draft/users/{user_id}` | `RemoveDraftBrandingUserRequest` | bool |
| POST | `/admin/v1/branding/draft/publish` | `PublishDraftBrandingRequest` | bool |
| GET | `/admin/v1/branding/custom_messaging` | `GetCustomMessagingRequest` | CustomMessaging |
| POST | `/admin/v1/branding/custom_messaging` | `ModifyCustomMessagingRequest` | CustomMessaging |

### Info

`DuoSecurity::info()`, the Account Info section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/admin/v1/info/summary` | `GetAccountSummaryRequest` | AccountSummary |
| GET | `/admin/v1/info/dashboard_summary` | `GetDashboardSummaryRequest` | DashboardSummary |
| GET | `/admin/v1/info/telephony_credits_used` | `GetTelephonyCreditsUsedRequest` | TelephonyCreditsUsed |
| GET | `/admin/v1/info/authentication_attempts` | `GetAuthenticationAttemptsRequest` | AuthenticationAttempts |
| GET | `/admin/v1/info/user_authentication_attempts` | `GetUserAuthenticationAttemptsRequest` | UserAuthenticationAttempts |

## Auth API

### Auth

`DuoSecurity::auth()`, the Auth API section.

| Method | Path | Request | Returns |
|---|---|---|---|
| GET | `/auth/v2/ping` | `PingRequest` | ServerTime |
| GET | `/auth/v2/check` | `CheckRequest` | ServerTime |
| GET | `/auth/v2/logo` | `LogoRequest` | string |
| POST | `/auth/v2/enroll` | `EnrollRequest` | Enrollment |
| POST | `/auth/v2/enroll_status` | `EnrollStatusRequest` | mixed |
| POST | `/auth/v2/preauth` | `PreauthRequest` | PreauthResult |
| POST | `/auth/v2/auth` | `AuthRequest` | AuthResult |
| GET | `/auth/v2/auth_status` | `AuthStatusRequest` | AuthStatus |

