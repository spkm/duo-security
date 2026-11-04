<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Admin\Data\Phones\SmsActivation;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Text a Duo Mobile activation code to a phone: POST /admin/v1/phones/{phone_id}/send_sms_activation. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: valid_secs, install, installation_msg, activation_msg.
 */
class SendPhoneSmsActivationRequest extends WriteRequest
{
    protected ?string $dto = SmsActivation::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: valid_secs, install, installation_msg, activation_msg.
     */
    public function __construct(protected string $phoneId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/phones/'.Path::segment($this->phoneId).'/send_sms_activation';
    }
}
