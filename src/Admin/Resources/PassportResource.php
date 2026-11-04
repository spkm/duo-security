<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Passport\PassportConfiguration;
use spkm\DuoSecurity\Admin\Requests\Passport\GetPassportConfigurationRequest;
use spkm\DuoSecurity\Admin\Requests\Passport\ModifyPassportConfigurationRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Passport endpoints of the Admin API, from DuoSecurity::passport().
 */
class PassportResource extends BaseResource
{
    /**
     * Retrieve the Duo Passport configuration: GET /admin/v2/passport/config.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getPassportConfiguration(array $params = []): PassportConfiguration
    {
        return $this->fetch(new GetPassportConfigurationRequest($params));
    }

    /**
     * Modify the Duo Passport configuration: POST /admin/v2/passport/config.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: enabled_status, enabled_groups, custom_supported_browsers, disabled_groups.
     */
    public function modifyPassportConfiguration(array $params = []): bool
    {
        return $this->perform(new ModifyPassportConfigurationRequest($params));
    }
}
