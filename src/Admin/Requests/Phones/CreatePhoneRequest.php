<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Create a phone: POST /admin/v1/phones. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: number, name, extension, type, platform, predelay, postdelay.
 */
class CreatePhoneRequest extends WriteRequest
{
    protected ?string $dto = Phone::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: number, name, extension, type, platform, predelay, postdelay.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/phones';
    }
}
