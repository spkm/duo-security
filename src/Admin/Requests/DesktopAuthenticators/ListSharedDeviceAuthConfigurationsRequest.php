<?php

namespace spkm\DuoSecurity\Admin\Requests\DesktopAuthenticators;

use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\SharedDeviceAuth;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve the shared device authentication configurations: GET /admin/v1/desktop_authenticators/shared_device_auth. Requires the "Grant resource - Read" permission.
 *
 * Parameters Duo documents: limit, offset.
 */
class ListSharedDeviceAuthConfigurationsRequest extends ListRequest
{
    protected ?string $dto = SharedDeviceAuth::class;

    protected int $maxPageSize = 500;

    protected bool $cacheable = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/desktop_authenticators/shared_device_auth';
    }
}
