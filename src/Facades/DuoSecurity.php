<?php

namespace spkm\DuoSecurity\Facades;

use Illuminate\Support\Facades\Facade;
use spkm\DuoSecurity\DuoSecurityService;

/**
 * @method static \spkm\DuoSecurity\Admin\Resources\AdminRolesResource adminRoles()
 * @method static \spkm\DuoSecurity\Admin\Resources\AdministrativeUnitsResource administrativeUnits()
 * @method static \spkm\DuoSecurity\Admin\Resources\AdministratorsResource administrators()
 * @method static \spkm\DuoSecurity\Admin\Resources\BillingResource billing()
 * @method static \spkm\DuoSecurity\Admin\Resources\BrandingResource branding()
 * @method static \spkm\DuoSecurity\Admin\Resources\BulkResource bulk()
 * @method static \spkm\DuoSecurity\Admin\Resources\BypassCodesResource bypassCodes()
 * @method static \spkm\DuoSecurity\Admin\Resources\DesktopAuthenticatorsResource desktopAuthenticators()
 * @method static \spkm\DuoSecurity\Admin\Resources\EndpointsResource endpoints()
 * @method static \spkm\DuoSecurity\Admin\Resources\GroupsResource groups()
 * @method static \spkm\DuoSecurity\Admin\Resources\IdentityVerificationResource identityVerification()
 * @method static \spkm\DuoSecurity\Admin\Resources\InfoResource info()
 * @method static \spkm\DuoSecurity\Admin\Resources\IntegrationsResource integrations()
 * @method static \spkm\DuoSecurity\Admin\Resources\LogsResource logs()
 * @method static \spkm\DuoSecurity\Admin\Resources\PassportResource passport()
 * @method static \spkm\DuoSecurity\Admin\Resources\PhonesResource phones()
 * @method static \spkm\DuoSecurity\Admin\Resources\PoliciesResource policies()
 * @method static \spkm\DuoSecurity\Admin\Resources\RegisteredDevicesResource registeredDevices()
 * @method static \spkm\DuoSecurity\Admin\Resources\SettingsResource settings()
 * @method static \spkm\DuoSecurity\Admin\Resources\SharedDeviceAuthResource sharedDeviceAuth()
 * @method static \spkm\DuoSecurity\Admin\Resources\SubaccountsResource subaccounts()
 * @method static \spkm\DuoSecurity\Admin\Resources\TokensResource tokens()
 * @method static \spkm\DuoSecurity\Admin\Resources\TrustMonitorResource trustMonitor()
 * @method static \spkm\DuoSecurity\Admin\Resources\UsersResource users()
 * @method static \spkm\DuoSecurity\Admin\Resources\WebAuthnCredentialsResource webAuthnCredentials()
 * @method static \spkm\DuoSecurity\Admin\AdminConnector admin(?string $connection = null)
 * @method static \spkm\DuoSecurity\Admin\AdminConnector connection(string $name)
 * @method static \spkm\DuoSecurity\Auth\Resources\AuthResource auth(?string $connection = null)
 * @method static \spkm\DuoSecurity\Auth\AuthConnector authConnector(?string $connection = null)
 * @method static \Saloon\Http\Response send(\Saloon\Http\Request $request, ?\Saloon\Http\Faking\MockClient $mockClient = null, ?callable $handleRetry = null)
 * @method static \Saloon\PaginationPlugin\Paginator paginate(\Saloon\Http\Request $request, ?int $perPage = null)
 * @method static \Saloon\Http\Response get(string $path, array<string, mixed> $query = [])
 * @method static \Saloon\Http\Response post(string $path, array<string, mixed>|\spkm\DuoSecurity\Data\Data|\JsonSerializable $body = [])
 * @method static \Saloon\Http\Response put(string $path, array<string, mixed>|\spkm\DuoSecurity\Data\Data|\JsonSerializable $body = [])
 * @method static \Saloon\Http\Response delete(string $path, array<string, mixed> $query = [])
 * @method static \spkm\DuoSecurity\Signing\Credentials credentials()
 * @method static string credentialsFingerprint()
 *
 * @see DuoSecurityService
 */
class DuoSecurity extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'duo-security';
    }
}
