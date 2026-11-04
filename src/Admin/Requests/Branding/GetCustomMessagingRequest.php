<?php

namespace spkm\DuoSecurity\Admin\Requests\Branding;

use spkm\DuoSecurity\Admin\Data\Branding\CustomMessaging;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the custom messaging shown to users: GET /admin/v1/branding/custom_messaging. Requires the "Grant settings" permission.
 */
class GetCustomMessagingRequest extends GetRequest
{
    protected ?string $dto = CustomMessaging::class;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/branding/custom_messaging';
    }
}
