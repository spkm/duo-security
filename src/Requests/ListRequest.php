<?php

namespace spkm\DuoSecurity\Requests;

use Saloon\Enums\Method;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\DuoSecurity\Support\Payload;

/**
 * A GET for a list of records, paged by limit and offset: Duo answers a page at a time, with the offset of the next
 * page in its metadata until the last, and caps the limit at what the endpoint allows (100 to 1000). Any parameters
 * the endpoint takes (a username to match, a type) go in the query string too; DuoPaginator pages through every
 * record.
 */
abstract class ListRequest extends DuoRequest implements Paginatable
{
    protected Method $method = Method::GET;

    protected string $responseShape = self::LIST;

    /**
     * The most records the endpoint returns at once.
     */
    protected int $maxPageSize = 100;

    /**
     * Whether the endpoint's mintime and maxtime are Unix timestamps in milliseconds (Trust Monitor's events).
     */
    protected bool $millisecondTimes = false;

    /** @var array<string, mixed> */
    protected array $params = [];

    /**
     * @param  array<string, mixed>  $params  The query parameters the endpoint documents, including limit and offset for one page.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function maxPageSize(): int
    {
        return $this->maxPageSize;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return Payload::parameters($this->params, $this->millisecondTimes ? ['mintime', 'maxtime'] : []);
    }
}
