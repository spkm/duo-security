<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Admin\Data\Phones\ActivationUrl;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Create a Duo Mobile activation code for a phone, as a URL and a barcode: POST /admin/v1/phones/{phone_id}/activation_url. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: valid_secs, install.
 */
class CreatePhoneActivationUrlRequest extends WriteRequest
{
    protected ?string $dto = ActivationUrl::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: valid_secs, install.
     */
    public function __construct(protected string $phoneId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/phones/'.Path::segment($this->phoneId).'/activation_url';
    }
}
