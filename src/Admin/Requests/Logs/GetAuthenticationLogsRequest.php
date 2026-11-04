<?php

namespace spkm\DuoSecurity\Admin\Requests\Logs;

use spkm\DuoSecurity\Admin\Data\Logs\AuthenticationLogEvent;
use spkm\DuoSecurity\Requests\LogRequest;

/**
 * Retrieve authentication events: GET /admin/v2/logs/authentication. Requires the "Grant read log" permission.
 *
 * Duo keeps 180 days of events, holds the latest two minutes back, and asks to be polled no more than once a minute. mintime and maxtime are Unix timestamps in milliseconds (a date is converted).
 *
 * Parameters Duo documents: mintime, maxtime, limit, next_offset, sort, applications, users, groups, assessment, detections, event_types, factors, formatter, phone_numbers, reasons, results, tokens.
 */
class GetAuthenticationLogsRequest extends LogRequest
{
    protected ?string $dto = AuthenticationLogEvent::class;

    protected int $maxPageSize = 1000;

    protected ?string $itemsKey = 'authlogs';

    public function resolveEndpoint(): string
    {
        return '/admin/v2/logs/authentication';
    }
}
