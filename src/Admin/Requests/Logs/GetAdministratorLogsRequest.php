<?php

namespace spkm\DuoSecurity\Admin\Requests\Logs;

use spkm\DuoSecurity\Admin\Data\Logs\AdministratorLogEvent;
use spkm\DuoSecurity\Requests\LogRequest;

/**
 * Retrieve administrator events (v1): the 1000 earliest after mintime, in seconds: GET /admin/v1/logs/administrator. Requires the "Grant read log" permission.
 *
 * Duo recommends the activity log (v2) instead.
 *
 * Parameters Duo documents: mintime.
 *
 * @deprecated Duo deprecated this endpoint.
 */
class GetAdministratorLogsRequest extends LogRequest
{
    protected ?string $dto = AdministratorLogEvent::class;

    protected int $maxPageSize = 1000;

    protected string $pagedBy = self::BY_TIME;

    protected bool $millisecondTimes = false;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/logs/administrator';
    }
}
