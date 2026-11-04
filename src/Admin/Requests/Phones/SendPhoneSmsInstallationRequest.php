<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Admin\Data\Phones\SmsInstallation;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Text a Duo Mobile installation link to a phone: POST /admin/v1/phones/{phone_id}/send_sms_installation. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: installation_msg.
 */
class SendPhoneSmsInstallationRequest extends WriteRequest
{
    protected ?string $dto = SmsInstallation::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: installation_msg.
     */
    public function __construct(protected string $phoneId, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/phones/'.Path::segment($this->phoneId).'/send_sms_installation';
    }
}
