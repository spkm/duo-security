<?php

namespace spkm\DuoSecurity\Admin\Resources;

use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\Bulk\BulkOperationResult;
use spkm\DuoSecurity\Admin\Requests\Bulk\RunBulkOperationsRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Bulk Operations endpoints of the Admin API, from DuoSecurity::bulk().
 */
class BulkResource extends BaseResource
{
    /**
     * Run up to 50 user operations in one call, in order (at most 50 calls a minute): POST /admin/v1/bulk.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: operations.
     * @return Collection<int, BulkOperationResult>
     */
    public function runBulkOperations(array $params = []): Collection
    {
        return $this->fetch(new RunBulkOperationsRequest($params));
    }
}
