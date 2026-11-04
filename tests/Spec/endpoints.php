<?php

use spkm\DuoSecurity\Admin\Data\AdministrativeUnits\AdministrativeUnit;
use spkm\DuoSecurity\Admin\Data\Administrators\ActivationLink;
use spkm\DuoSecurity\Admin\Data\Administrators\Admin;
use spkm\DuoSecurity\Admin\Data\Administrators\AdminActivation;
use spkm\DuoSecurity\Admin\Data\Administrators\AdminSyncResult;
use spkm\DuoSecurity\Admin\Data\Administrators\AllowedAuthMethods;
use spkm\DuoSecurity\Admin\Data\Administrators\PasswordManagement;
use spkm\DuoSecurity\Admin\Data\AdminRoles\AdminRole;
use spkm\DuoSecurity\Admin\Data\Branding\Branding;
use spkm\DuoSecurity\Admin\Data\Branding\CustomMessaging;
use spkm\DuoSecurity\Admin\Data\Bulk\BulkOperationResult;
use spkm\DuoSecurity\Admin\Data\BypassCodes\BypassCode;
use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\DesktopAuthenticator;
use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\SharedDeviceAuth;
use spkm\DuoSecurity\Admin\Data\Endpoints\Endpoint;
use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Admin\Data\Groups\GroupMember;
use spkm\DuoSecurity\Admin\Data\IdentityVerification\IdentityVerification;
use spkm\DuoSecurity\Admin\Data\Info\AccountSummary;
use spkm\DuoSecurity\Admin\Data\Info\AuthenticationAttempts;
use spkm\DuoSecurity\Admin\Data\Info\DashboardSummary;
use spkm\DuoSecurity\Admin\Data\Info\TelephonyCreditsUsed;
use spkm\DuoSecurity\Admin\Data\Info\UserAuthenticationAttempts;
use spkm\DuoSecurity\Admin\Data\Integrations\ClientSecret;
use spkm\DuoSecurity\Admin\Data\Integrations\Integration;
use spkm\DuoSecurity\Admin\Data\Integrations\SecretKey;
use spkm\DuoSecurity\Admin\Data\Logs\ActivityLogEvent;
use spkm\DuoSecurity\Admin\Data\Logs\AdministratorLogEvent;
use spkm\DuoSecurity\Admin\Data\Logs\AuthenticationLogEvent;
use spkm\DuoSecurity\Admin\Data\Logs\AuthorizationLogEvent;
use spkm\DuoSecurity\Admin\Data\Logs\OfflineEnrollmentEvent;
use spkm\DuoSecurity\Admin\Data\Logs\TelephonyLogEvent;
use spkm\DuoSecurity\Admin\Data\Passport\PassportConfiguration;
use spkm\DuoSecurity\Admin\Data\Phones\ActivationUrl;
use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Admin\Data\Phones\SmsActivation;
use spkm\DuoSecurity\Admin\Data\Phones\SmsInstallation;
use spkm\DuoSecurity\Admin\Data\Policies\Policy;
use spkm\DuoSecurity\Admin\Data\Policies\PolicyBatch;
use spkm\DuoSecurity\Admin\Data\Policies\PolicyCalculation;
use spkm\DuoSecurity\Admin\Data\Policies\PolicySummary;
use spkm\DuoSecurity\Admin\Data\RegisteredDevices\BlockedDevice;
use spkm\DuoSecurity\Admin\Data\RegisteredDevices\RegisteredDevice;
use spkm\DuoSecurity\Admin\Data\Settings\Settings;
use spkm\DuoSecurity\Admin\Data\Subaccounts\Edition;
use spkm\DuoSecurity\Admin\Data\Subaccounts\Subaccount;
use spkm\DuoSecurity\Admin\Data\Subaccounts\TelephonyCredits;
use spkm\DuoSecurity\Admin\Data\Subaccounts\UserLimit;
use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Admin\Data\TrustMonitor\TrustMonitorEvent;
use spkm\DuoSecurity\Admin\Data\Users\DirectorySync;
use spkm\DuoSecurity\Admin\Data\Users\DirectorySyncResult;
use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Admin\Data\Users\VerificationPush;
use spkm\DuoSecurity\Admin\Data\Users\VerificationPushResponse;
use spkm\DuoSecurity\Admin\Data\WebAuthn\WebAuthnCredential;
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
use spkm\DuoSecurity\Admin\Requests\AdminRoles\GetAdminRoleRequest;
use spkm\DuoSecurity\Admin\Requests\AdminRoles\ListAdminRolesRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\AddDraftBrandingUserRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\GetBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\GetCustomMessagingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\GetDraftBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\ModifyBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\ModifyCustomMessagingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\ModifyDraftBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\PublishDraftBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\RemoveDraftBrandingUserRequest;
use spkm\DuoSecurity\Admin\Requests\Bulk\RunBulkOperationsRequest;
use spkm\DuoSecurity\Admin\Requests\BypassCodes\DeleteBypassCodeRequest;
use spkm\DuoSecurity\Admin\Requests\BypassCodes\GetBypassCodeRequest;
use spkm\DuoSecurity\Admin\Requests\BypassCodes\ListBypassCodesRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\CreateSharedDeviceAuthConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\DeleteDesktopAuthenticatorRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\DeleteSharedDeviceAuthConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\GetDesktopAuthenticatorRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\GetSharedDeviceAuthConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\ListDesktopAuthenticatorsRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\ListSharedDeviceAuthConfigurationsRequest;
use spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators\UpdateSharedDeviceAuthConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\Endpoints\GetEndpointRequest;
use spkm\DuoSecurity\Admin\Requests\Endpoints\ListEndpointsRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\CreateGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\DeleteGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\GetGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\ListGroupMembersRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\ListGroupsRequest;
use spkm\DuoSecurity\Admin\Requests\Groups\ModifyGroupRequest;
use spkm\DuoSecurity\Admin\Requests\IdentityVerification\CancelIdentityVerificationRequest;
use spkm\DuoSecurity\Admin\Requests\IdentityVerification\GetIdentityVerificationStatusRequest;
use spkm\DuoSecurity\Admin\Requests\IdentityVerification\StartIdentityVerificationRequest;
use spkm\DuoSecurity\Admin\Requests\Info\GetAccountSummaryRequest;
use spkm\DuoSecurity\Admin\Requests\Info\GetAuthenticationAttemptsRequest;
use spkm\DuoSecurity\Admin\Requests\Info\GetDashboardSummaryRequest;
use spkm\DuoSecurity\Admin\Requests\Info\GetTelephonyCreditsUsedRequest;
use spkm\DuoSecurity\Admin\Requests\Info\GetUserAuthenticationAttemptsRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\CreateIntegrationRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\CreateIntegrationV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\DeleteIntegrationRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\DeleteIntegrationV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetIntegrationRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetIntegrationSecretKeyRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetIntegrationV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetOAuthClientSecretRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetOAuthClientSecretV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetOidcClientSecretRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetOidcClientSecretV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\ListIntegrationsRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\ListIntegrationsV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\ModifyIntegrationRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\ModifyIntegrationV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\ResetOAuthClientSecretRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\ResetOAuthClientSecretV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\ResetOidcClientSecretRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\ResetOidcClientSecretV2Request;
use spkm\DuoSecurity\Admin\Requests\Logs\GetActivityLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetAdministratorLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetAuthenticationLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetAuthorizationLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetOfflineEnrollmentLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetTelephonyLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Passport\GetPassportConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\Passport\ModifyPassportConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\CreatePhoneActivationUrlRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\CreatePhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\DeletePhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\GetPhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\ListPhonesRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\ModifyPhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\SendPhoneSmsActivationRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\SendPhoneSmsInstallationRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\SendPhoneSmsPasscodesRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\SetPhoneQuarantineStatusRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\CalculatePolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\CopyPolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\CreatePolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\DeletePolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\GetGlobalPolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\GetPoliciesSummaryRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\GetPolicyRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\ListPoliciesRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\UpdatePoliciesRequest;
use spkm\DuoSecurity\Admin\Requests\Policies\UpdatePolicyRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\BlockRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\BlockRegisteredDevicesRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\DeleteRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\GetBlockedRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\GetRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\ListBlockedRegisteredDevicesRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\ListRegisteredDevicesRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\UnblockRegisteredDeviceRequest;
use spkm\DuoSecurity\Admin\Requests\RegisteredDevices\UnblockRegisteredDevicesRequest;
use spkm\DuoSecurity\Admin\Requests\Settings\DeleteLogoRequest;
use spkm\DuoSecurity\Admin\Requests\Settings\GetLogoRequest;
use spkm\DuoSecurity\Admin\Requests\Settings\GetSettingsRequest;
use spkm\DuoSecurity\Admin\Requests\Settings\ModifyLogoRequest;
use spkm\DuoSecurity\Admin\Requests\Settings\ModifySettingsRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\CreateSubaccountRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\DeleteSubaccountRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\GetEditionRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\GetTelephonyCreditsRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\GetUserLimitRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\ListSubaccountsRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\SetEditionRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\SetTelephonyCreditsRequest;
use spkm\DuoSecurity\Admin\Requests\Subaccounts\SetUserLimitRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\CreateTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\DeleteTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\GetTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\ListTokensRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\ListU2fTokensRequest;
use spkm\DuoSecurity\Admin\Requests\Tokens\ResyncTokenRequest;
use spkm\DuoSecurity\Admin\Requests\TrustMonitor\ListTrustMonitorEventsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\AddUserPhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Users\AddUserToGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Users\AddUserTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Users\BulkCreateUsersRequest;
use spkm\DuoSecurity\Admin\Requests\Users\BulkRestoreUsersRequest;
use spkm\DuoSecurity\Admin\Requests\Users\BulkSendUsersToTrashRequest;
use spkm\DuoSecurity\Admin\Requests\Users\CreateUserBypassCodesRequest;
use spkm\DuoSecurity\Admin\Requests\Users\CreateUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\DeleteUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\EnrollUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\GetUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\GetUserVerificationPushResponseRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListDirectorySyncsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserBypassCodesRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserDesktopAuthenticatorsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserGroupsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserPhonesRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUsersRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserTokensRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserU2fTokensRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserWebAuthnCredentialsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ModifyUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\RemoveUserFromGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Users\RemoveUserPhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Users\RemoveUserTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Users\SendUserVerificationPushRequest;
use spkm\DuoSecurity\Admin\Requests\Users\SyncUserFromDirectoryRequest;
use spkm\DuoSecurity\Admin\Requests\WebAuthn\DeleteWebAuthnCredentialRequest;
use spkm\DuoSecurity\Admin\Requests\WebAuthn\GetWebAuthnCredentialRequest;
use spkm\DuoSecurity\Admin\Requests\WebAuthn\ListWebAuthnCredentialsRequest;
use spkm\DuoSecurity\Auth\Data\AuthResult;
use spkm\DuoSecurity\Auth\Data\AuthStatus;
use spkm\DuoSecurity\Auth\Data\Enrollment;
use spkm\DuoSecurity\Auth\Data\PreauthResult;
use spkm\DuoSecurity\Auth\Data\ServerTime;
use spkm\DuoSecurity\Auth\Requests\AuthRequest;
use spkm\DuoSecurity\Auth\Requests\AuthStatusRequest;
use spkm\DuoSecurity\Auth\Requests\CheckRequest;
use spkm\DuoSecurity\Auth\Requests\EnrollRequest;
use spkm\DuoSecurity\Auth\Requests\EnrollStatusRequest;
use spkm\DuoSecurity\Auth\Requests\LogoRequest;
use spkm\DuoSecurity\Auth\Requests\PingRequest;
use spkm\DuoSecurity\Auth\Requests\PreauthRequest;

/*
 * Every request the package has, keyed by the operation Duo documents: the class, its constructor arguments, the
 * method and path sent, the response's shape, the model, whether it carries a body, and whether it is paged.
 */
return [
    'POST /accounts/v1/account/list' => [ListSubaccountsRequest::class, [], 'POST', '/accounts/v1/account/list', 'list', Subaccount::class, true, false],
    'POST /accounts/v1/account/create' => [CreateSubaccountRequest::class, ['NAME1'], 'POST', '/accounts/v1/account/create', 'one', Subaccount::class, true, false],
    'POST /accounts/v1/account/delete' => [DeleteSubaccountRequest::class, ['ACCOUNTID1'], 'POST', '/accounts/v1/account/delete', 'none', null, true, false],
    'GET /admin/v1/billing/edition' => [GetEditionRequest::class, ['ACCOUNTID1'], 'GET', '/admin/v1/billing/edition', 'one', Edition::class, false, false],
    'POST /admin/v1/billing/edition' => [SetEditionRequest::class, ['ACCOUNTID1', 'EDITION1'], 'POST', '/admin/v1/billing/edition', 'none', null, true, false],
    'GET /admin/v1/billing/telephony_credits' => [GetTelephonyCreditsRequest::class, ['ACCOUNTID1'], 'GET', '/admin/v1/billing/telephony_credits', 'one', TelephonyCredits::class, false, false],
    'POST /admin/v1/billing/telephony_credits' => [SetTelephonyCreditsRequest::class, ['ACCOUNTID1'], 'POST', '/admin/v1/billing/telephony_credits', 'one', TelephonyCredits::class, true, false],
    'GET /admin/v1/billing/user_limit' => [GetUserLimitRequest::class, ['ACCOUNTID1'], 'GET', '/admin/v1/billing/user_limit', 'one', UserLimit::class, false, false],
    'POST /admin/v1/billing/user_limit' => [SetUserLimitRequest::class, ['ACCOUNTID1'], 'POST', '/admin/v1/billing/user_limit', 'none', null, true, false],
    'GET /admin/v1/users' => [ListUsersRequest::class, [], 'GET', '/admin/v1/users', 'list', User::class, false, true],
    'POST /admin/v1/users' => [CreateUserRequest::class, [], 'POST', '/admin/v1/users', 'one', User::class, true, false],
    'POST /admin/v1/users/bulk_create' => [BulkCreateUsersRequest::class, [], 'POST', '/admin/v1/users/bulk_create', 'list', User::class, true, false],
    'POST /admin/v1/users/bulk_restore' => [BulkRestoreUsersRequest::class, [], 'POST', '/admin/v1/users/bulk_restore', 'raw', null, true, false],
    'POST /admin/v1/users/bulk_send_to_trash' => [BulkSendUsersToTrashRequest::class, [], 'POST', '/admin/v1/users/bulk_send_to_trash', 'raw', null, true, false],
    'GET /admin/v1/users/{user_id}' => [GetUserRequest::class, ['USERID1'], 'GET', '/admin/v1/users/USERID1', 'one', User::class, false, false],
    'POST /admin/v1/users/{user_id}' => [ModifyUserRequest::class, ['USERID1'], 'POST', '/admin/v1/users/USERID1', 'one', User::class, true, false],
    'DELETE /admin/v1/users/{user_id}' => [DeleteUserRequest::class, ['USERID1'], 'DELETE', '/admin/v1/users/USERID1', 'none', null, false, false],
    'POST /admin/v1/users/enroll' => [EnrollUserRequest::class, ['USERNAME1', 'EMAIL1'], 'POST', '/admin/v1/users/enroll', 'raw', null, true, false],
    'GET /admin/v1/users/{user_id}/bypass_codes' => [ListUserBypassCodesRequest::class, ['USERID1'], 'GET', '/admin/v1/users/USERID1/bypass_codes', 'list', BypassCode::class, false, true],
    'POST /admin/v1/users/{user_id}/bypass_codes' => [CreateUserBypassCodesRequest::class, ['USERID1'], 'POST', '/admin/v1/users/USERID1/bypass_codes', 'list', null, true, false],
    'GET /admin/v1/users/{user_id}/groups' => [ListUserGroupsRequest::class, ['USERID1'], 'GET', '/admin/v1/users/USERID1/groups', 'list', Group::class, false, true],
    'POST /admin/v1/users/{user_id}/groups' => [AddUserToGroupRequest::class, ['USERID1', 'GROUPID1'], 'POST', '/admin/v1/users/USERID1/groups', 'none', null, true, false],
    'DELETE /admin/v1/users/{user_id}/groups/{group_id}' => [RemoveUserFromGroupRequest::class, ['USERID1', 'GROUPID1'], 'DELETE', '/admin/v1/users/USERID1/groups/GROUPID1', 'none', null, false, false],
    'GET /admin/v1/users/{user_id}/phones' => [ListUserPhonesRequest::class, ['USERID1'], 'GET', '/admin/v1/users/USERID1/phones', 'list', Phone::class, false, true],
    'POST /admin/v1/users/{user_id}/phones' => [AddUserPhoneRequest::class, ['USERID1', 'PHONEID1'], 'POST', '/admin/v1/users/USERID1/phones', 'none', null, true, false],
    'DELETE /admin/v1/users/{user_id}/phones/{phone_id}' => [RemoveUserPhoneRequest::class, ['USERID1', 'PHONEID1'], 'DELETE', '/admin/v1/users/USERID1/phones/PHONEID1', 'none', null, false, false],
    'GET /admin/v1/users/{user_id}/tokens' => [ListUserTokensRequest::class, ['USERID1'], 'GET', '/admin/v1/users/USERID1/tokens', 'list', Token::class, false, true],
    'POST /admin/v1/users/{user_id}/tokens' => [AddUserTokenRequest::class, ['USERID1', 'TOKENID1'], 'POST', '/admin/v1/users/USERID1/tokens', 'none', null, true, false],
    'DELETE /admin/v1/users/{user_id}/tokens/{token_id}' => [RemoveUserTokenRequest::class, ['USERID1', 'TOKENID1'], 'DELETE', '/admin/v1/users/USERID1/tokens/TOKENID1', 'none', null, false, false],
    'GET /admin/v1/users/{user_id}/u2ftokens' => [ListUserU2fTokensRequest::class, ['USERID1'], 'GET', '/admin/v1/users/USERID1/u2ftokens', 'list', null, false, false],
    'GET /admin/v1/users/{user_id}/webauthncredentials' => [ListUserWebAuthnCredentialsRequest::class, ['USERID1'], 'GET', '/admin/v1/users/USERID1/webauthncredentials', 'list', WebAuthnCredential::class, false, false],
    'GET /admin/v1/users/{user_id}/desktopauthenticators' => [ListUserDesktopAuthenticatorsRequest::class, ['USERID1'], 'GET', '/admin/v1/users/USERID1/desktopauthenticators', 'list', DesktopAuthenticator::class, false, true],
    'GET /admin/v1/users/directorysync' => [ListDirectorySyncsRequest::class, [], 'GET', '/admin/v1/users/directorysync', 'list', DirectorySync::class, false, false],
    'POST /admin/v1/users/directorysync/{directory_key}/syncuser' => [SyncUserFromDirectoryRequest::class, ['DIRECTORYKEY1', 'USERNAME1'], 'POST', '/admin/v1/users/directorysync/DIRECTORYKEY1/syncuser', 'one', DirectorySyncResult::class, true, false],
    'POST /admin/v1/users/{user_id}/send_verification_push' => [SendUserVerificationPushRequest::class, ['USERID1', 'PHONEID1'], 'POST', '/admin/v1/users/USERID1/send_verification_push', 'one', VerificationPush::class, true, false],
    'GET /admin/v1/users/{user_id}/verification_push_response' => [GetUserVerificationPushResponseRequest::class, ['USERID1', 'PUSHID1'], 'GET', '/admin/v1/users/USERID1/verification_push_response', 'one', VerificationPushResponse::class, false, false],
    'POST /admin/v1/bulk' => [RunBulkOperationsRequest::class, [], 'POST', '/admin/v1/bulk', 'list', BulkOperationResult::class, true, false],
    'GET /admin/v1/groups' => [ListGroupsRequest::class, [], 'GET', '/admin/v1/groups', 'list', Group::class, false, true],
    'POST /admin/v1/groups' => [CreateGroupRequest::class, ['NAME1'], 'POST', '/admin/v1/groups', 'one', Group::class, true, false],
    'GET /admin/v2/groups/{group_id}' => [GetGroupRequest::class, ['GROUPID1'], 'GET', '/admin/v2/groups/GROUPID1', 'one', Group::class, false, false],
    'GET /admin/v2/groups/{group_id}/users' => [ListGroupMembersRequest::class, ['GROUPID1'], 'GET', '/admin/v2/groups/GROUPID1/users', 'list', GroupMember::class, false, true],
    'POST /admin/v1/groups/{group_id}' => [ModifyGroupRequest::class, ['GROUPID1'], 'POST', '/admin/v1/groups/GROUPID1', 'one', Group::class, true, false],
    'DELETE /admin/v1/groups/{group_id}' => [DeleteGroupRequest::class, ['GROUPID1'], 'DELETE', '/admin/v1/groups/GROUPID1', 'none', null, false, false],
    'POST /admin/v2/identity_verification/{user_id}/start' => [StartIdentityVerificationRequest::class, ['USERID1'], 'POST', '/admin/v2/identity_verification/USERID1/start', 'one', IdentityVerification::class, true, false],
    'GET /admin/v2/identity_verification/{user_id}/status' => [GetIdentityVerificationStatusRequest::class, ['USERID1'], 'GET', '/admin/v2/identity_verification/USERID1/status', 'one', IdentityVerification::class, false, false],
    'POST /admin/v2/identity_verification/{user_id}/cancel' => [CancelIdentityVerificationRequest::class, ['USERID1', 'INQUIRYID1'], 'POST', '/admin/v2/identity_verification/USERID1/cancel', 'none', null, true, false],
    'GET /admin/v1/phones' => [ListPhonesRequest::class, [], 'GET', '/admin/v1/phones', 'list', Phone::class, false, true],
    'POST /admin/v1/phones' => [CreatePhoneRequest::class, [], 'POST', '/admin/v1/phones', 'one', Phone::class, true, false],
    'GET /admin/v1/phones/{phone_id}' => [GetPhoneRequest::class, ['PHONEID1'], 'GET', '/admin/v1/phones/PHONEID1', 'one', Phone::class, false, false],
    'POST /admin/v1/phones/{phone_id}' => [ModifyPhoneRequest::class, ['PHONEID1'], 'POST', '/admin/v1/phones/PHONEID1', 'one', Phone::class, true, false],
    'POST /admin/v2/phones/{phone_id}/quarantine_status' => [SetPhoneQuarantineStatusRequest::class, ['PHONEID1', 'QUARANTINESTATUS1'], 'POST', '/admin/v2/phones/PHONEID1/quarantine_status', 'none', null, true, false],
    'DELETE /admin/v1/phones/{phone_id}' => [DeletePhoneRequest::class, ['PHONEID1'], 'DELETE', '/admin/v1/phones/PHONEID1', 'none', null, false, false],
    'POST /admin/v1/phones/{phone_id}/activation_url' => [CreatePhoneActivationUrlRequest::class, ['PHONEID1'], 'POST', '/admin/v1/phones/PHONEID1/activation_url', 'one', ActivationUrl::class, true, false],
    'POST /admin/v1/phones/{phone_id}/send_sms_activation' => [SendPhoneSmsActivationRequest::class, ['PHONEID1'], 'POST', '/admin/v1/phones/PHONEID1/send_sms_activation', 'one', SmsActivation::class, true, false],
    'POST /admin/v1/phones/{phone_id}/send_sms_installation' => [SendPhoneSmsInstallationRequest::class, ['PHONEID1'], 'POST', '/admin/v1/phones/PHONEID1/send_sms_installation', 'one', SmsInstallation::class, true, false],
    'POST /admin/v1/phones/{phone_id}/send_sms_passcodes' => [SendPhoneSmsPasscodesRequest::class, ['PHONEID1'], 'POST', '/admin/v1/phones/PHONEID1/send_sms_passcodes', 'none', null, true, false],
    'GET /admin/v1/tokens' => [ListTokensRequest::class, [], 'GET', '/admin/v1/tokens', 'list', Token::class, false, true],
    'POST /admin/v1/tokens' => [CreateTokenRequest::class, ['TYPE1', 'SERIAL1'], 'POST', '/admin/v1/tokens', 'one', Token::class, true, false],
    'GET /admin/v1/tokens/{token_id}' => [GetTokenRequest::class, ['TOKENID1'], 'GET', '/admin/v1/tokens/TOKENID1', 'one', Token::class, false, false],
    'POST /admin/v1/tokens/{token_id}/resync' => [ResyncTokenRequest::class, ['TOKENID1', 'CODE11', 'CODE21', 'CODE31'], 'POST', '/admin/v1/tokens/TOKENID1/resync', 'none', null, true, false],
    'DELETE /admin/v1/tokens/{token_id}' => [DeleteTokenRequest::class, ['TOKENID1'], 'DELETE', '/admin/v1/tokens/TOKENID1', 'none', null, false, false],
    'GET /admin/v1/u2ftokens' => [ListU2fTokensRequest::class, [], 'GET', '/admin/v1/u2ftokens', 'list', null, false, false],
    'GET /admin/v1/webauthncredentials' => [ListWebAuthnCredentialsRequest::class, [], 'GET', '/admin/v1/webauthncredentials', 'list', WebAuthnCredential::class, false, true],
    'GET /admin/v1/webauthncredentials/{webauthnkey}' => [GetWebAuthnCredentialRequest::class, ['WEBAUTHNKEY1'], 'GET', '/admin/v1/webauthncredentials/WEBAUTHNKEY1', 'one', WebAuthnCredential::class, false, false],
    'DELETE /admin/v1/webauthncredentials/{webauthnkey}' => [DeleteWebAuthnCredentialRequest::class, ['WEBAUTHNKEY1'], 'DELETE', '/admin/v1/webauthncredentials/WEBAUTHNKEY1', 'none', null, false, false],
    'GET /admin/v1/desktop_authenticators' => [ListDesktopAuthenticatorsRequest::class, [], 'GET', '/admin/v1/desktop_authenticators', 'list', DesktopAuthenticator::class, false, true],
    'GET /admin/v1/desktop_authenticators/{dakey}' => [GetDesktopAuthenticatorRequest::class, ['DAKEY1'], 'GET', '/admin/v1/desktop_authenticators/DAKEY1', 'one', DesktopAuthenticator::class, false, false],
    'DELETE /admin/v1/desktop_authenticators/{dakey}' => [DeleteDesktopAuthenticatorRequest::class, ['DAKEY1'], 'DELETE', '/admin/v1/desktop_authenticators/DAKEY1', 'none', null, false, false],
    'GET /admin/v1/desktop_authenticators/shared_device_auth' => [ListSharedDeviceAuthConfigurationsRequest::class, [], 'GET', '/admin/v1/desktop_authenticators/shared_device_auth', 'list', SharedDeviceAuth::class, false, true],
    'GET /admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}' => [GetSharedDeviceAuthConfigurationRequest::class, ['SHAREDDEVICEKEY1'], 'GET', '/admin/v1/desktop_authenticators/shared_device_auth/SHAREDDEVICEKEY1', 'one', SharedDeviceAuth::class, false, false],
    'POST /admin/v1/desktop_authenticators/shared_device_auth' => [CreateSharedDeviceAuthConfigurationRequest::class, [], 'POST', '/admin/v1/desktop_authenticators/shared_device_auth', 'one', SharedDeviceAuth::class, true, false],
    'PUT /admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}' => [UpdateSharedDeviceAuthConfigurationRequest::class, ['SHAREDDEVICEKEY1'], 'PUT', '/admin/v1/desktop_authenticators/shared_device_auth/SHAREDDEVICEKEY1', 'one', SharedDeviceAuth::class, true, false],
    'DELETE /admin/v1/desktop_authenticators/shared_device_auth/{shared_device_key}' => [DeleteSharedDeviceAuthConfigurationRequest::class, ['SHAREDDEVICEKEY1'], 'DELETE', '/admin/v1/desktop_authenticators/shared_device_auth/SHAREDDEVICEKEY1', 'none', null, false, false],
    'GET /admin/v1/bypass_codes' => [ListBypassCodesRequest::class, [], 'GET', '/admin/v1/bypass_codes', 'list', BypassCode::class, false, true],
    'GET /admin/v1/bypass_codes/{bypass_code_id}' => [GetBypassCodeRequest::class, ['BYPASSCODEID1'], 'GET', '/admin/v1/bypass_codes/BYPASSCODEID1', 'one', BypassCode::class, false, false],
    'DELETE /admin/v1/bypass_codes/{bypass_code_id}' => [DeleteBypassCodeRequest::class, ['BYPASSCODEID1'], 'DELETE', '/admin/v1/bypass_codes/BYPASSCODEID1', 'none', null, false, false],
    'GET /admin/v3/integrations' => [ListIntegrationsRequest::class, [], 'GET', '/admin/v3/integrations', 'list', Integration::class, false, true],
    'POST /admin/v3/integrations' => [CreateIntegrationRequest::class, ['NAME1', 'TYPE1'], 'POST', '/admin/v3/integrations', 'one', Integration::class, true, false],
    'GET /admin/v3/integrations/{integration_key}' => [GetIntegrationRequest::class, ['INTEGRATIONKEY1'], 'GET', '/admin/v3/integrations/INTEGRATIONKEY1', 'one', Integration::class, false, false],
    'POST /admin/v3/integrations/{integration_key}' => [ModifyIntegrationRequest::class, ['INTEGRATIONKEY1'], 'POST', '/admin/v3/integrations/INTEGRATIONKEY1', 'one', Integration::class, true, false],
    'DELETE /admin/v3/integrations/{integration_key}' => [DeleteIntegrationRequest::class, ['INTEGRATIONKEY1'], 'DELETE', '/admin/v3/integrations/INTEGRATIONKEY1', 'none', null, false, false],
    'GET /admin/v1/integrations/{integration_key}/skey' => [GetIntegrationSecretKeyRequest::class, ['INTEGRATIONKEY1'], 'GET', '/admin/v1/integrations/INTEGRATIONKEY1/skey', 'one', SecretKey::class, false, false],
    'GET /admin/v3/integrations/oauth_cc/{integration_key}/client_secret/{client_id}' => [GetOAuthClientSecretRequest::class, ['INTEGRATIONKEY1', 'CLIENTID1'], 'GET', '/admin/v3/integrations/oauth_cc/INTEGRATIONKEY1/client_secret/CLIENTID1', 'one', ClientSecret::class, false, false],
    'POST /admin/v3/integrations/oauth_cc/{integration_key}/client_secret/{client_id}' => [ResetOAuthClientSecretRequest::class, ['INTEGRATIONKEY1', 'CLIENTID1'], 'POST', '/admin/v3/integrations/oauth_cc/INTEGRATIONKEY1/client_secret/CLIENTID1', 'none', null, true, false],
    'GET /admin/v3/integrations/oidc/{integration_key}/client_secret' => [GetOidcClientSecretRequest::class, ['INTEGRATIONKEY1'], 'GET', '/admin/v3/integrations/oidc/INTEGRATIONKEY1/client_secret', 'one', ClientSecret::class, false, false],
    'POST /admin/v3/integrations/oidc/{integration_key}/client_secret' => [ResetOidcClientSecretRequest::class, ['INTEGRATIONKEY1'], 'POST', '/admin/v3/integrations/oidc/INTEGRATIONKEY1/client_secret', 'none', null, true, false],
    'GET /admin/v2/integrations' => [ListIntegrationsV2Request::class, [], 'GET', '/admin/v2/integrations', 'list', Integration::class, false, true],
    'POST /admin/v2/integrations' => [CreateIntegrationV2Request::class, ['NAME1', 'TYPE1'], 'POST', '/admin/v2/integrations', 'one', Integration::class, true, false],
    'GET /admin/v2/integrations/{integration_key}' => [GetIntegrationV2Request::class, ['INTEGRATIONKEY1'], 'GET', '/admin/v2/integrations/INTEGRATIONKEY1', 'one', Integration::class, false, false],
    'POST /admin/v2/integrations/{integration_key}' => [ModifyIntegrationV2Request::class, ['INTEGRATIONKEY1'], 'POST', '/admin/v2/integrations/INTEGRATIONKEY1', 'one', Integration::class, true, false],
    'DELETE /admin/v2/integrations/{integration_key}' => [DeleteIntegrationV2Request::class, ['INTEGRATIONKEY1'], 'DELETE', '/admin/v2/integrations/INTEGRATIONKEY1', 'none', null, false, false],
    'GET /admin/v2/integrations/oauth_cc/{integration_key}/client_secret/{client_id}' => [GetOAuthClientSecretV2Request::class, ['INTEGRATIONKEY1', 'CLIENTID1'], 'GET', '/admin/v2/integrations/oauth_cc/INTEGRATIONKEY1/client_secret/CLIENTID1', 'one', ClientSecret::class, false, false],
    'POST /admin/v2/integrations/oauth_cc/{integration_key}/client_secret/{client_id}' => [ResetOAuthClientSecretV2Request::class, ['INTEGRATIONKEY1', 'CLIENTID1'], 'POST', '/admin/v2/integrations/oauth_cc/INTEGRATIONKEY1/client_secret/CLIENTID1', 'none', null, true, false],
    'GET /admin/v2/integrations/oidc/{integration_key}/client_secret' => [GetOidcClientSecretV2Request::class, ['INTEGRATIONKEY1'], 'GET', '/admin/v2/integrations/oidc/INTEGRATIONKEY1/client_secret', 'one', ClientSecret::class, false, false],
    'POST /admin/v2/integrations/oidc/{integration_key}/client_secret' => [ResetOidcClientSecretV2Request::class, ['INTEGRATIONKEY1'], 'POST', '/admin/v2/integrations/oidc/INTEGRATIONKEY1/client_secret', 'none', null, true, false],
    'GET /admin/v2/policies/summary' => [GetPoliciesSummaryRequest::class, [], 'GET', '/admin/v2/policies/summary', 'one', PolicySummary::class, false, false],
    'GET /admin/v2/policies' => [ListPoliciesRequest::class, [], 'GET', '/admin/v2/policies', 'list', Policy::class, false, true],
    'GET /admin/v2/policies/{policy_key}' => [GetPolicyRequest::class, ['POLICYKEY1'], 'GET', '/admin/v2/policies/POLICYKEY1', 'one', Policy::class, false, false],
    'GET /admin/v2/policies/global' => [GetGlobalPolicyRequest::class, [], 'GET', '/admin/v2/policies/global', 'one', Policy::class, false, false],
    'GET /admin/v2/policies/calculate' => [CalculatePolicyRequest::class, ['USERID1', 'INTEGRATIONKEY1'], 'GET', '/admin/v2/policies/calculate', 'one', PolicyCalculation::class, false, false],
    'POST /admin/v2/policies/copy' => [CopyPolicyRequest::class, ['POLICYKEY1'], 'POST', '/admin/v2/policies/copy', 'one', PolicyBatch::class, true, false],
    'POST /admin/v2/policies' => [CreatePolicyRequest::class, ['POLICYNAME1'], 'POST', '/admin/v2/policies', 'one', Policy::class, true, false],
    'PUT /admin/v2/policies/update' => [UpdatePoliciesRequest::class, [], 'PUT', '/admin/v2/policies/update', 'one', PolicyBatch::class, true, false],
    'PUT /admin/v2/policies/{policy_key}' => [UpdatePolicyRequest::class, ['POLICYKEY1'], 'PUT', '/admin/v2/policies/POLICYKEY1', 'one', Policy::class, true, false],
    'DELETE /admin/v2/policies/{policy_key}' => [DeletePolicyRequest::class, ['POLICYKEY1'], 'DELETE', '/admin/v2/policies/POLICYKEY1', 'none', null, false, false],
    'GET /admin/v1/endpoints' => [ListEndpointsRequest::class, [], 'GET', '/admin/v1/endpoints', 'list', Endpoint::class, false, true],
    'GET /admin/v1/endpoints/{epkey}' => [GetEndpointRequest::class, ['EPKEY1'], 'GET', '/admin/v1/endpoints/EPKEY1', 'one', Endpoint::class, false, false],
    'GET /admin/v1/registered_devices' => [ListRegisteredDevicesRequest::class, [], 'GET', '/admin/v1/registered_devices', 'list', RegisteredDevice::class, false, true],
    'GET /admin/v1/registered_devices/{compkey}' => [GetRegisteredDeviceRequest::class, ['COMPKEY1'], 'GET', '/admin/v1/registered_devices/COMPKEY1', 'one', RegisteredDevice::class, false, false],
    'DELETE /admin/v1/registered_devices/{compkey}' => [DeleteRegisteredDeviceRequest::class, ['COMPKEY1'], 'DELETE', '/admin/v1/registered_devices/COMPKEY1', 'none', null, false, false],
    'GET /admin/v1/registered_devices/blocked' => [ListBlockedRegisteredDevicesRequest::class, [], 'GET', '/admin/v1/registered_devices/blocked', 'list', BlockedDevice::class, false, true],
    'GET /admin/v1/registered_devices/blocked/{compkey}' => [GetBlockedRegisteredDeviceRequest::class, ['COMPKEY1'], 'GET', '/admin/v1/registered_devices/blocked/COMPKEY1', 'one', BlockedDevice::class, false, false],
    'POST /admin/v1/registered_devices/blocked' => [BlockRegisteredDevicesRequest::class, [], 'POST', '/admin/v1/registered_devices/blocked', 'list', BlockedDevice::class, true, false],
    'POST /admin/v1/registered_devices/blocked/{compkey}' => [BlockRegisteredDeviceRequest::class, ['COMPKEY1'], 'POST', '/admin/v1/registered_devices/blocked/COMPKEY1', 'one', BlockedDevice::class, true, false],
    'DELETE /admin/v1/registered_devices/blocked' => [UnblockRegisteredDevicesRequest::class, [], 'DELETE', '/admin/v1/registered_devices/blocked', 'none', null, false, false],
    'DELETE /admin/v1/registered_devices/blocked/{compkey}' => [UnblockRegisteredDeviceRequest::class, ['COMPKEY1'], 'DELETE', '/admin/v1/registered_devices/blocked/COMPKEY1', 'none', null, false, false],
    'GET /admin/v2/passport/config' => [GetPassportConfigurationRequest::class, [], 'GET', '/admin/v2/passport/config', 'one', PassportConfiguration::class, false, false],
    'POST /admin/v2/passport/config' => [ModifyPassportConfigurationRequest::class, [], 'POST', '/admin/v2/passport/config', 'none', null, true, false],
    'GET /admin/v1/admins' => [ListAdministratorsRequest::class, [], 'GET', '/admin/v1/admins', 'list', Admin::class, false, true],
    'POST /admin/v1/admins' => [CreateAdministratorRequest::class, ['EMAIL1', 'NAME1'], 'POST', '/admin/v1/admins', 'one', Admin::class, true, false],
    'GET /admin/v1/admins/{admin_id}' => [GetAdministratorRequest::class, ['ADMINID1'], 'GET', '/admin/v1/admins/ADMINID1', 'one', Admin::class, false, false],
    'POST /admin/v1/admins/{admin_id}' => [ModifyAdministratorRequest::class, ['ADMINID1'], 'POST', '/admin/v1/admins/ADMINID1', 'one', Admin::class, true, false],
    'DELETE /admin/v1/admins/{admin_id}' => [DeleteAdministratorRequest::class, ['ADMINID1'], 'DELETE', '/admin/v1/admins/ADMINID1', 'none', null, false, false],
    'POST /admin/v1/admins/{admin_id}/reset' => [ResetAdministratorAuthAttemptsRequest::class, ['ADMINID1'], 'POST', '/admin/v1/admins/ADMINID1/reset', 'none', null, true, false],
    'POST /admin/v1/admins/{admin_id}/clear_inactivity' => [ClearAdministratorInactivityRequest::class, ['ADMINID1'], 'POST', '/admin/v1/admins/ADMINID1/clear_inactivity', 'none', null, true, false],
    'POST /admin/v1/admins/{admin_id}/activation_link' => [CreateAdministratorActivationLinkRequest::class, ['ADMINID1'], 'POST', '/admin/v1/admins/ADMINID1/activation_link', 'one', Admin::class, true, false],
    'POST /admin/v1/admins/{admin_id}/activation_link/email' => [EmailAdministratorActivationLinkRequest::class, ['ADMINID1'], 'POST', '/admin/v1/admins/ADMINID1/activation_link/email', 'one', ActivationLink::class, true, false],
    'DELETE /admin/v1/admins/{admin_id}/activation_link' => [DeleteAdministratorActivationLinkRequest::class, ['ADMINID1'], 'DELETE', '/admin/v1/admins/ADMINID1/activation_link', 'one', Admin::class, false, false],
    'POST /admin/v1/admins/activations' => [CreateAdministratorActivationRequest::class, ['EMAIL1'], 'POST', '/admin/v1/admins/activations', 'one', AdminActivation::class, true, false],
    'GET /admin/v1/admins/activations' => [ListAdministratorActivationsRequest::class, [], 'GET', '/admin/v1/admins/activations', 'list', AdminActivation::class, false, true],
    'DELETE /admin/v1/admins/activations/{admin_activation_id}' => [DeleteAdministratorActivationRequest::class, ['ADMINACTIVATIONID1'], 'DELETE', '/admin/v1/admins/activations/ADMINACTIVATIONID1', 'none', null, false, false],
    'POST /admin/v1/admins/directorysync/{directory_key}/syncadmin' => [SyncAdministratorFromDirectoryRequest::class, ['DIRECTORYKEY1', 'EMAIL1'], 'POST', '/admin/v1/admins/directorysync/DIRECTORYKEY1/syncadmin', 'one', AdminSyncResult::class, true, false],
    'GET /admin/v1/admins/password_mgmt' => [ListAdministratorPasswordManagementRequest::class, [], 'GET', '/admin/v1/admins/password_mgmt', 'list', PasswordManagement::class, false, true],
    'GET /admin/v1/admins/{admin_id}/password_mgmt' => [GetAdministratorPasswordManagementRequest::class, ['ADMINID1'], 'GET', '/admin/v1/admins/ADMINID1/password_mgmt', 'one', PasswordManagement::class, false, false],
    'POST /admin/v1/admins/{admin_id}/password_mgmt' => [SetAdministratorPasswordManagementRequest::class, ['ADMINID1'], 'POST', '/admin/v1/admins/ADMINID1/password_mgmt', 'one', PasswordManagement::class, true, false],
    'GET /admin/v1/admins/allowed_auth_methods' => [GetAllowedAdministratorAuthMethodsRequest::class, [], 'GET', '/admin/v1/admins/allowed_auth_methods', 'one', AllowedAuthMethods::class, false, false],
    'POST /admin/v1/admins/allowed_auth_methods' => [SetAllowedAdministratorAuthMethodsRequest::class, [], 'POST', '/admin/v1/admins/allowed_auth_methods', 'one', AllowedAuthMethods::class, true, false],
    'GET /admin/v1/admin_roles' => [ListAdminRolesRequest::class, [], 'GET', '/admin/v1/admin_roles', 'list', AdminRole::class, false, false],
    'GET /admin/v1/admin_roles/{role_id}' => [GetAdminRoleRequest::class, ['ROLEID1'], 'GET', '/admin/v1/admin_roles/ROLEID1', 'one', AdminRole::class, false, false],
    'GET /admin/v1/administrative_units' => [ListAdministrativeUnitsRequest::class, [], 'GET', '/admin/v1/administrative_units', 'list', AdministrativeUnit::class, false, true],
    'GET /admin/v1/administrative_units/{admin_unit_id}' => [GetAdministrativeUnitRequest::class, ['ADMINUNITID1'], 'GET', '/admin/v1/administrative_units/ADMINUNITID1', 'one', AdministrativeUnit::class, false, false],
    'POST /admin/v1/administrative_units' => [CreateAdministrativeUnitRequest::class, ['NAME1', 'DESCRIPTION1'], 'POST', '/admin/v1/administrative_units', 'one', AdministrativeUnit::class, true, false],
    'POST /admin/v1/administrative_units/{admin_unit_id}' => [ModifyAdministrativeUnitRequest::class, ['ADMINUNITID1'], 'POST', '/admin/v1/administrative_units/ADMINUNITID1', 'one', AdministrativeUnit::class, true, false],
    'POST /admin/v1/administrative_units/{admin_unit_id}/admin/{admin_id}' => [AddAdministrativeUnitAdminRequest::class, ['ADMINUNITID1', 'ADMINID1'], 'POST', '/admin/v1/administrative_units/ADMINUNITID1/admin/ADMINID1', 'one', AdministrativeUnit::class, true, false],
    'DELETE /admin/v1/administrative_units/{admin_unit_id}/admin/{admin_id}' => [RemoveAdministrativeUnitAdminRequest::class, ['ADMINUNITID1', 'ADMINID1'], 'DELETE', '/admin/v1/administrative_units/ADMINUNITID1/admin/ADMINID1', 'one', AdministrativeUnit::class, false, false],
    'POST /admin/v1/administrative_units/{admin_unit_id}/group/{group_id}' => [AddAdministrativeUnitGroupRequest::class, ['ADMINUNITID1', 'GROUPID1'], 'POST', '/admin/v1/administrative_units/ADMINUNITID1/group/GROUPID1', 'one', AdministrativeUnit::class, true, false],
    'DELETE /admin/v1/administrative_units/{admin_unit_id}/group/{group_id}' => [RemoveAdministrativeUnitGroupRequest::class, ['ADMINUNITID1', 'GROUPID1'], 'DELETE', '/admin/v1/administrative_units/ADMINUNITID1/group/GROUPID1', 'one', AdministrativeUnit::class, false, false],
    'POST /admin/v1/administrative_units/{admin_unit_id}/integration/{integration_key}' => [AddAdministrativeUnitIntegrationRequest::class, ['ADMINUNITID1', 'INTEGRATIONKEY1'], 'POST', '/admin/v1/administrative_units/ADMINUNITID1/integration/INTEGRATIONKEY1', 'one', AdministrativeUnit::class, true, false],
    'DELETE /admin/v1/administrative_units/{admin_unit_id}/integration/{integration_key}' => [RemoveAdministrativeUnitIntegrationRequest::class, ['ADMINUNITID1', 'INTEGRATIONKEY1'], 'DELETE', '/admin/v1/administrative_units/ADMINUNITID1/integration/INTEGRATIONKEY1', 'one', AdministrativeUnit::class, false, false],
    'DELETE /admin/v1/administrative_units/{admin_unit_id}' => [DeleteAdministrativeUnitRequest::class, ['ADMINUNITID1'], 'DELETE', '/admin/v1/administrative_units/ADMINUNITID1', 'none', null, false, false],
    'GET /admin/v2/logs/authentication' => [GetAuthenticationLogsRequest::class, [], 'GET', '/admin/v2/logs/authentication', 'list', AuthenticationLogEvent::class, false, true],
    'GET /admin/v2/logs/authorization' => [GetAuthorizationLogsRequest::class, [], 'GET', '/admin/v2/logs/authorization', 'list', AuthorizationLogEvent::class, false, true],
    'GET /admin/v2/logs/activity' => [GetActivityLogsRequest::class, [], 'GET', '/admin/v2/logs/activity', 'list', ActivityLogEvent::class, false, true],
    'GET /admin/v2/logs/telephony' => [GetTelephonyLogsRequest::class, [], 'GET', '/admin/v2/logs/telephony', 'list', TelephonyLogEvent::class, false, true],
    'GET /admin/v1/logs/administrator' => [GetAdministratorLogsRequest::class, [], 'GET', '/admin/v1/logs/administrator', 'list', AdministratorLogEvent::class, false, true],
    'GET /admin/v1/logs/offline_enrollment' => [GetOfflineEnrollmentLogsRequest::class, [], 'GET', '/admin/v1/logs/offline_enrollment', 'list', OfflineEnrollmentEvent::class, false, true],
    'GET /admin/v1/trust_monitor/events' => [ListTrustMonitorEventsRequest::class, [], 'GET', '/admin/v1/trust_monitor/events', 'list', TrustMonitorEvent::class, false, true],
    'GET /admin/v1/settings' => [GetSettingsRequest::class, [], 'GET', '/admin/v1/settings', 'one', Settings::class, false, false],
    'POST /admin/v1/settings' => [ModifySettingsRequest::class, [], 'POST', '/admin/v1/settings', 'one', Settings::class, true, false],
    'GET /admin/v1/logo' => [GetLogoRequest::class, [], 'GET', '/admin/v1/logo', 'text', null, false, false],
    'POST /admin/v1/logo' => [ModifyLogoRequest::class, ['LOGO1'], 'POST', '/admin/v1/logo', 'none', null, true, false],
    'DELETE /admin/v1/logo' => [DeleteLogoRequest::class, [], 'DELETE', '/admin/v1/logo', 'none', null, false, false],
    'GET /admin/v1/branding' => [GetBrandingRequest::class, [], 'GET', '/admin/v1/branding', 'one', Branding::class, false, false],
    'POST /admin/v1/branding' => [ModifyBrandingRequest::class, [], 'POST', '/admin/v1/branding', 'one', Branding::class, true, false],
    'GET /admin/v1/branding/draft' => [GetDraftBrandingRequest::class, [], 'GET', '/admin/v1/branding/draft', 'one', Branding::class, false, false],
    'POST /admin/v1/branding/draft' => [ModifyDraftBrandingRequest::class, [], 'POST', '/admin/v1/branding/draft', 'one', Branding::class, true, false],
    'POST /admin/v1/branding/draft/users/{user_id}' => [AddDraftBrandingUserRequest::class, ['USERID1'], 'POST', '/admin/v1/branding/draft/users/USERID1', 'none', null, true, false],
    'DELETE /admin/v1/branding/draft/users/{user_id}' => [RemoveDraftBrandingUserRequest::class, ['USERID1'], 'DELETE', '/admin/v1/branding/draft/users/USERID1', 'none', null, false, false],
    'POST /admin/v1/branding/draft/publish' => [PublishDraftBrandingRequest::class, [], 'POST', '/admin/v1/branding/draft/publish', 'none', null, true, false],
    'GET /admin/v1/branding/custom_messaging' => [GetCustomMessagingRequest::class, [], 'GET', '/admin/v1/branding/custom_messaging', 'one', CustomMessaging::class, false, false],
    'POST /admin/v1/branding/custom_messaging' => [ModifyCustomMessagingRequest::class, [], 'POST', '/admin/v1/branding/custom_messaging', 'one', CustomMessaging::class, true, false],
    'GET /admin/v1/info/summary' => [GetAccountSummaryRequest::class, [], 'GET', '/admin/v1/info/summary', 'one', AccountSummary::class, false, false],
    'GET /admin/v1/info/dashboard_summary' => [GetDashboardSummaryRequest::class, [], 'GET', '/admin/v1/info/dashboard_summary', 'one', DashboardSummary::class, false, false],
    'GET /admin/v1/info/telephony_credits_used' => [GetTelephonyCreditsUsedRequest::class, [], 'GET', '/admin/v1/info/telephony_credits_used', 'one', TelephonyCreditsUsed::class, false, false],
    'GET /admin/v1/info/authentication_attempts' => [GetAuthenticationAttemptsRequest::class, [], 'GET', '/admin/v1/info/authentication_attempts', 'one', AuthenticationAttempts::class, false, false],
    'GET /admin/v1/info/user_authentication_attempts' => [GetUserAuthenticationAttemptsRequest::class, [], 'GET', '/admin/v1/info/user_authentication_attempts', 'one', UserAuthenticationAttempts::class, false, false],
    'GET /auth/v2/ping' => [PingRequest::class, [], 'GET', '/auth/v2/ping', 'one', ServerTime::class, false, false],
    'GET /auth/v2/check' => [CheckRequest::class, [], 'GET', '/auth/v2/check', 'one', ServerTime::class, false, false],
    'GET /auth/v2/logo' => [LogoRequest::class, [], 'GET', '/auth/v2/logo', 'text', null, false, false],
    'POST /auth/v2/enroll' => [EnrollRequest::class, [], 'POST', '/auth/v2/enroll', 'one', Enrollment::class, true, false],
    'POST /auth/v2/enroll_status' => [EnrollStatusRequest::class, ['USERID1', 'ACTIVATIONCODE1'], 'POST', '/auth/v2/enroll_status', 'raw', null, true, false],
    'POST /auth/v2/preauth' => [PreauthRequest::class, [], 'POST', '/auth/v2/preauth', 'one', PreauthResult::class, true, false],
    'POST /auth/v2/auth' => [AuthRequest::class, [], 'POST', '/auth/v2/auth', 'one', AuthResult::class, true, false],
    'GET /auth/v2/auth_status' => [AuthStatusRequest::class, ['TXID1'], 'GET', '/auth/v2/auth_status', 'one', AuthStatus::class, false, false],
];
