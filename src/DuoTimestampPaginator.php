<?php

namespace spkm\DuoSecurity;

use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use spkm\DuoSecurity\Requests\LogRequest;

/**
 * Pages through a v1 log (administrator, offline enrollment), which answers the 1000 earliest events after
 * mintime: the next page asks from a second after the last event, until a page is short.
 */
class DuoTimestampPaginator extends DuoPaginator
{
    protected ?int $perPageLimit = 1000;

    protected ?int $nextMintime = null;

    protected ?int $windowEnd = null;

    public function __construct(Connector $connector, Request $request, ?int $perPage = null)
    {
        parent::__construct($connector, $request);

        $this->perPageLimit = $request instanceof LogRequest ? $request->maxPageSize() : ($perPage ?? 1000);
        $this->windowEnd = $request instanceof LogRequest ? $request->windowEnd() : null;
    }

    protected function applyPagination(Request $request): Request
    {
        if ($this->nextMintime !== null) {
            $request->query()->add('mintime', $this->nextMintime);
        }

        return $request;
    }

    protected function isLastPage(Response $response): bool
    {
        $items = $this->getPageItems($response, $response->getRequest());

        if ($items === [] || count($items) < (int) $this->perPageLimit) {
            return true;
        }

        $latest = null;

        foreach ($items as $item) {
            $timestamp = self::timestampOf($item);

            if ($timestamp !== null && ($latest === null || $timestamp > $latest)) {
                $latest = $timestamp;
            }
        }

        if ($latest === null || $latest + 1 === $this->nextMintime || ($this->windowEnd !== null && $latest >= $this->windowEnd)) {
            return true;
        }

        $this->nextMintime = $latest + 1;

        return false;
    }

    /**
     * The page's events, without those past the window end.
     *
     * @return list<mixed>
     */
    protected function getPageItems(Response $response, Request $request): array
    {
        $items = parent::getPageItems($response, $request);

        if ($this->windowEnd === null) {
            return $items;
        }

        return array_values(array_filter($items, fn (mixed $item) => (self::timestampOf($item) ?? 0) <= $this->windowEnd));
    }

    private static function timestampOf(mixed $item): ?int
    {
        return is_array($item) && is_numeric($item['timestamp'] ?? null) ? (int) $item['timestamp'] : null;
    }

    protected function onRewind(): void
    {
        parent::onRewind();

        $this->nextMintime = null;
    }
}
