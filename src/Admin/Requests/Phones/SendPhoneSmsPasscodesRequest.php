<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Text a new batch of passcodes to a phone: POST /admin/v1/phones/{phone_id}/send_sms_passcodes. Requires the "Grant resource - Write" permission.
 */
class SendPhoneSmsPasscodesRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(protected string $phoneId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/phones/'.Path::segment($this->phoneId).'/send_sms_passcodes';
    }
}
