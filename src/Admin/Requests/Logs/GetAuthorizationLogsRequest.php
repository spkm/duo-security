<?php

namespace spkm\DuoSecurity\Admin\Requests\Logs;

use spkm\DuoSecurity\Admin\Data\Logs\AuthorizationLogEvent;
use spkm\DuoSecurity\Requests\LogRequest;

/**
 * Retrieve authorization decisions: GET /admin/v2/logs/authorization. Requires the "Grant read log" permission.
 *
 * Duo keeps 180 days of events, holds the latest two minutes back, and asks to be polled no more than once a minute. mintime and maxtime are Unix timestamps in milliseconds (a date is converted).
 *
 * Parameters Duo documents: mintime, maxtime, limit, next_offset, sort.
 */
class GetAuthorizationLogsRequest extends LogRequest
{
    protected ?string $dto = AuthorizationLogEvent::class;

    protected int $maxPageSize = 1000;

    protected ?string $itemsKey = 'items';

    public function resolveEndpoint(): string
    {
        return '/admin/v2/logs/authorization';
    }
}
