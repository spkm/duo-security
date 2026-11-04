<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\TrustMonitor\TrustMonitorEvent;
use spkm\DuoSecurity\Admin\Requests\TrustMonitor\ListTrustMonitorEventsRequest;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Trust Monitor endpoints of the Admin API, from DuoSecurity::trustMonitor().
 */
class TrustMonitorResource extends BaseResource
{
    /**
     * Retrieve Trust Monitor events surfaced between two times (in milliseconds): GET /admin/v1/trust_monitor/events.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime, limit, offset, type.
     * @return ListQuery<TrustMonitorEvent>
     */
    public function listTrustMonitorEvents(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListTrustMonitorEventsRequest([...$params, ...$page]), TrustMonitorEvent::class);
    }
}
