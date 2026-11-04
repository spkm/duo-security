<?php

namespace spkm\DuoSecurity\Admin\Requests\Logs;

use spkm\DuoSecurity\Admin\Data\Logs\TelephonyLogEvent;
use spkm\DuoSecurity\Requests\LogRequest;

/**
 * Retrieve telephony events: the calls and texts made and their credits: GET /admin/v2/logs/telephony. Requires the "Grant read log" permission.
 *
 * Duo keeps 180 days of events, holds the latest two minutes back, and asks to be polled no more than once a minute. mintime and maxtime are Unix timestamps in milliseconds (a date is converted).
 *
 * Parameters Duo documents: mintime, maxtime, limit, next_offset, sort.
 */
class GetTelephonyLogsRequest extends LogRequest
{
    protected ?string $dto = TelephonyLogEvent::class;

    protected int $maxPageSize = 1000;

    protected ?string $itemsKey = 'items';

    public function resolveEndpoint(): string
    {
        return '/admin/v2/logs/telephony';
    }
}
