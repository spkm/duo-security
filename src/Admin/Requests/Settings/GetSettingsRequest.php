<?php

namespace spkm\DuoSecurity\Admin\Requests\Settings;

use spkm\DuoSecurity\Admin\Data\Settings\Settings;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the account's settings: GET /admin/v1/settings. Requires the "Grant settings" permission.
 */
class GetSettingsRequest extends GetRequest
{
    protected ?string $dto = Settings::class;

    protected bool $cacheable = true;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/settings';
    }
}
