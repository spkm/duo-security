<?php

namespace spkm\DuoSecurity\Admin\Requests\Passport;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Modify the Duo Passport configuration: POST /admin/v2/passport/config. Requires the "Grant resource - Write" permission.
 *
 * Needs v5 signing, which this package uses.
 *
 * Parameters Duo documents: enabled_status, enabled_groups, custom_supported_browsers, disabled_groups.
 */
class ModifyPassportConfigurationRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: enabled_status, enabled_groups, custom_supported_browsers, disabled_groups.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/passport/config';
    }
}
