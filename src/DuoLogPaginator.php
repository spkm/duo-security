<?php

namespace spkm\DuoSecurity;

use JsonException;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use spkm\DuoSecurity\Requests\LogRequest;
use spkm\DuoSecurity\Support\Envelope;

/**
 * Pages through a v2 log (authentication, authorization, activity, telephony) by the next_offset cursor Duo gives
 * in each page's metadata ("timestamp,txid"), sent back as it came, until a page has none.
 */
class DuoLogPaginator extends DuoPaginator
{
    protected ?int $perPageLimit = 1000;

    public function __construct(Connector $connector, Request $request, ?int $perPage = null)
    {
        parent::__construct($connector, $request);

        $max = $request instanceof LogRequest ? $request->maxPageSize() : 1000;
        $this->perPageLimit = max(1, min($perPage ?? 1000, $max));
    }

    protected function applyPagination(Request $request): Request
    {
        $request->query()->add('limit', $this->perPageLimit);

        if ($this->nextOffset !== null) {
            $request->query()->add('next_offset', (string) $this->nextOffset);
        } else {
            $request->query()->remove('next_offset');
        }

        return $request;
    }

    protected function isLastPage(Response $response): bool
    {
        if ($this->getPageItems($response, $response->getRequest()) === []) {
            return true;
        }

        try {
            $next = Envelope::nextOffset($response);
        } catch (JsonException) {
            return true;
        }

        if ($next === null || (string) $next === (string) $this->nextOffset) {
            return true;
        }

        $this->nextOffset = (string) $next;

        return false;
    }
}
