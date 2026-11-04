<?php

namespace spkm\DuoSecurity;

use Illuminate\Support\LazyCollection;
use JsonException;
use LogicException;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Paginator;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\Exceptions\ServerErrorException;
use spkm\DuoSecurity\Requests\DuoRequest;
use spkm\DuoSecurity\Requests\ListRequest;
use spkm\DuoSecurity\Support\Envelope;

/**
 * Pages through a Duo list by limit and offset: from an offset of 0, then from the next_offset Duo gives in each
 * page's metadata, until a page has none (or nothing in it). Trust Monitor's events page the same way.
 */
class DuoPaginator extends Paginator
{
    protected ?int $perPageLimit = 100;

    protected int|string|null $nextOffset = null;

    /**
     * Where the first page starts: the offset the request asked for (skip()), else 0.
     */
    protected int $startOffset = 0;

    protected ?string $lastPageFingerprint = null;

    protected ?Response $lastResponse = null;

    /** @var list<mixed> */
    protected array $lastItems = [];

    public function __construct(Connector $connector, Request $request, ?int $perPage = null)
    {
        parent::__construct($connector, $request);

        $max = $request instanceof ListRequest ? $request->maxPageSize() : null;
        $this->perPageLimit = max(1, min($perPage ?? (int) $this->perPageLimit, $max ?? PHP_INT_MAX));
        $this->startOffset = $request instanceof ListRequest && is_numeric($request->query()->get('offset')) ? (int) $request->query()->get('offset') : 0;
    }

    /**
     * Every item on every page, as the request's models.
     *
     * @return LazyCollection<int, Data>
     */
    public function dtos(): LazyCollection
    {
        $request = $this->getOriginalRequest();
        $dto = $request instanceof DuoRequest ? $request->dtoClass() : null;

        if ($dto === null) {
            throw new LogicException(class_basename($request).' has no model; use collect() for its items as arrays.');
        }

        return $this->models($dto);
    }

    /**
     * Every item on every page as the given model.
     *
     * @template TModel of Data
     *
     * @param  class-string<TModel>  $model
     * @return LazyCollection<int, TModel>
     */
    public function models(string $model): LazyCollection
    {
        return $this->collect()->map(fn (mixed $item) => $model::from((array) $item));
    }

    protected function applyPagination(Request $request): Request
    {
        $request->query()->add('limit', $this->perPageLimit);
        $request->query()->add('offset', $this->nextOffset ?? $this->startOffset);

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

        if ($next === null) {
            return true;
        }

        $this->nextOffset = $next;

        return false;
    }

    protected function onRewind(): void
    {
        $this->nextOffset = null;
        $this->lastPageFingerprint = null;
        $this->lastResponse = null;
        $this->lastItems = [];
    }

    /**
     * @return list<mixed>
     */
    protected function getPageItems(Response $response, Request $request): array
    {
        if (! Envelope::succeeded($response)) {
            throw DuoApiException::fromResponse($response);
        }

        if ($response === $this->lastResponse) {
            return $this->lastItems;
        }

        try {
            $items = Envelope::items($response, $request instanceof DuoRequest ? $request->itemsKey() : null);
        } catch (JsonException) {
            throw new ServerErrorException($response);
        }

        // An endpoint that ignores the offset would answer the same page forever: a page repeated with the same next
        // offset is the end (two pages alike that lead on differently are both kept).
        $fingerprint = sha1((string) json_encode([$items, Envelope::nextOffset($response)]));
        $this->lastResponse = $response;
        $this->lastItems = $fingerprint === $this->lastPageFingerprint ? [] : $items;
        $this->lastPageFingerprint = $fingerprint;

        return $this->lastItems;
    }
}
