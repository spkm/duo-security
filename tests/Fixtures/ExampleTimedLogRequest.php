<?php

namespace spkm\DuoSecurity\Tests\Fixtures;

use spkm\DuoSecurity\Requests\LogRequest;

class ExampleTimedLogRequest extends LogRequest
{
    protected int $maxPageSize = 3;

    protected string $pagedBy = self::BY_TIME;

    protected bool $millisecondTimes = false;

    public function resolveEndpoint(): string
    {
        return '/admin/v1/logs/examples';
    }
}
