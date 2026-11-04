<?php

namespace spkm\DuoSecurity\Admin\Requests\Info;

use spkm\DuoSecurity\Admin\Data\Info\DashboardSummary;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the Admin Panel dashboard's summary for a period (times in seconds): GET /admin/v1/info/dashboard_summary. Requires the "Grant read information" permission.
 *
 * Parameters Duo documents: mintime, maxtime.
 */
class GetDashboardSummaryRequest extends GetRequest
{
    protected ?string $dto = DashboardSummary::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/info/dashboard_summary';
    }
}
