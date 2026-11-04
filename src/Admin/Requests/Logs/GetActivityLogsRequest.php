<?php

namespace spkm\DuoSecurity\Admin\Requests\Logs;

use spkm\DuoSecurity\Admin\Data\Logs\ActivityLogEvent;
use spkm\DuoSecurity\Requests\LogRequest;

/**
 * Retrieve activity events: administrator and configuration changes: GET /admin/v2/logs/activity. Requires the "Grant read log" permission.
 *
 * Duo keeps 180 days of events, holds the latest two minutes back, and asks to be polled no more than once a minute. mintime and maxtime are Unix timestamps in milliseconds (a date is converted).
 *
 * Parameters Duo documents: mintime, maxtime, limit, next_offset, sort.
 */
class GetActivityLogsRequest extends LogRequest
{
    protected ?string $dto = ActivityLogEvent::class;

    protected int $maxPageSize = 1000;

    protected ?string $itemsKey = 'items';

    public function resolveEndpoint(): string
    {
        return '/admin/v2/logs/activity';
    }
}
