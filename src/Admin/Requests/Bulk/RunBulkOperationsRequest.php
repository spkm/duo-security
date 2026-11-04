<?php

namespace spkm\DuoSecurity\Admin\Requests\Bulk;

use spkm\DuoSecurity\Admin\Data\Bulk\BulkOperationResult;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Run up to 50 user operations in one call, in order (at most 50 calls a minute): POST /admin/v1/bulk. Requires the "Grant resource - Write" permission.
 *
 * Parameters Duo documents: operations.
 */
class RunBulkOperationsRequest extends WriteRequest
{
    protected ?string $dto = BulkOperationResult::class;

    protected string $responseShape = self::LIST;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: operations.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/bulk';
    }
}
