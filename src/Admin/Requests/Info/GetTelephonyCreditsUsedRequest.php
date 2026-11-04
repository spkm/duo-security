<?php

namespace spkm\DuoSecurity\Admin\Requests\Info;

use spkm\DuoSecurity\Admin\Data\Info\TelephonyCreditsUsed;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the telephony credits used in a period (times in seconds): GET /admin/v1/info/telephony_credits_used. Requires the "Grant read information" permission.
 *
 * Parameters Duo documents: mintime, maxtime.
 */
class GetTelephonyCreditsUsedRequest extends GetRequest
{
    protected ?string $dto = TelephonyCreditsUsed::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/info/telephony_credits_used';
    }
}
