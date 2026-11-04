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

    public function __construct(Connector $connector, Request $request, ?int $perPage = null)
    {
        parent::__construct($connector, $request);

        $this->perPageLimit = $request instanceof LogRequest ? $request->maxPageSize() : ($perPage ?? 1000);
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
            $timestamp = is_array($item) && is_numeric($item['timestamp'] ?? null) ? (int) $item['timestamp'] : null;

            if ($timestamp !== null && ($latest === null || $timestamp > $latest)) {
                $latest = $timestamp;
            }
        }

        if ($latest === null || $latest + 1 === $this->nextMintime) {
            return true;
        }

        $this->nextMintime = $latest + 1;

        return false;
    }

    protected function onRewind(): void
    {
        parent::onRewind();

        $this->nextMintime = null;
    }
}
