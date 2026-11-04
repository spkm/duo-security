<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Requests\GetRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Retrieve a phone: GET /admin/v1/phones/{phone_id}. Requires the "Grant resource - Read" permission.
 */
class GetPhoneRequest extends GetRequest
{
    protected ?string $dto = Phone::class;

    protected bool $cacheable = true;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $phoneId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/phones/'.Path::segment($this->phoneId);
    }
}
