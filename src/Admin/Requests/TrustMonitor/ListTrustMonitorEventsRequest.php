<?php

namespace spkm\DuoSecurity\Admin\Requests\TrustMonitor;

use spkm\DuoSecurity\Admin\Data\TrustMonitor\TrustMonitorEvent;
use spkm\DuoSecurity\Requests\ListRequest;

/**
 * Retrieve Trust Monitor events surfaced between two times (in milliseconds): GET /admin/v1/trust_monitor/events. Requires the "Grant read log" permission.
 *
 * Parameters Duo documents: mintime, maxtime, limit, offset, type.
 */
class ListTrustMonitorEventsRequest extends ListRequest
{
    protected ?string $dto = TrustMonitorEvent::class;

    protected int $maxPageSize = 200;

    protected ?string $itemsKey = 'events';

    protected bool $millisecondTimes = true;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/trust_monitor/events';
    }
}
