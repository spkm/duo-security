<?php

namespace spkm\DuoSecurity\Admin\Requests\Info;

use spkm\DuoSecurity\Admin\Data\Info\AccountSummary;
use spkm\DuoSecurity\Requests\GetRequest;

/**
 * Retrieve the account's counts of users, admins, integrations, groups, phones and tokens: GET /admin/v1/info/summary. Requires the "Grant read information" permission.
 */
class GetAccountSummaryRequest extends GetRequest
{
    protected ?string $dto = AccountSummary::class;

    protected bool $cacheable = true;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/info/summary';
    }
}
