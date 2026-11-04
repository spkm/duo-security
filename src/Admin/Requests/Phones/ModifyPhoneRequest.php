<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Modify a phone: POST /admin/v1/phones/{phone_id}. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: number, name, extension, type, platform, predelay, postdelay.
 */
class ModifyPhoneRequest extends WriteRequest
{
    protected ?string $dto = Phone::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: number, name, extension, type, platform, predelay, postdelay.
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
