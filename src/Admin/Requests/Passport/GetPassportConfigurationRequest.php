<?php

namespace spkm\DuoSecurity\Admin\Requests\Passport;

use spkm\DuoSecurity\Admin\Data\Passport\PassportConfiguration;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the Duo Passport configuration: GET /admin/v2/passport/config. Requires the "Grant resource - Read" permission.
 *
 * Needs v5 signing, which this package uses.
 */
class GetPassportConfigurationRequest extends GetRequest
{
    protected ?string $dto = PassportConfiguration::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
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
