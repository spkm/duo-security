<?php

namespace spkm\DuoSecurity\Admin\Requests\Phones;

use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Quarantine a phone, or restore it (Duo Premier and Advantage): POST /admin/v2/phones/{phone_id}/quarantine_status. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: quarantine_status_reason.
 */
class SetPhoneQuarantineStatusRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: quarantine_status_reason.
     */
    public function __construct(protected string $phoneId, string $quarantineStatus, array $params = [])
    {
        $this->params = ['quarantine_status' => $quarantineStatus, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v2/phones/'.Path::segment($this->phoneId).'/quarantine_status';
    }
}
