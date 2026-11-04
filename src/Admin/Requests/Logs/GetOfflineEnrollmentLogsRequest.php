<?php

namespace spkm\DuoSecurity\Admin\Requests\Logs;

use spkm\DuoSecurity\Admin\Data\Logs\OfflineEnrollmentEvent;
use spkm\DuoSecurity\Requests\LogRequest;

/**
 * Retrieve offline enrollment events: the 1000 earliest after mintime, in seconds: GET /admin/v1/logs/offline_enrollment. Requires the "Grant read log" permission.
 *
 * Parameters Duo documents: mintime.
 */
class GetOfflineEnrollmentLogsRequest extends LogRequest
{
    protected ?string $dto = OfflineEnrollmentEvent::class;

    protected int $maxPageSize = 1000;

    protected string $pagedBy = self::BY_TIME;

    protected bool $millisecondTimes = false;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/logs/offline_enrollment';
    }
}
