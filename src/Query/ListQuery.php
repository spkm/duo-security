<?php

namespace spkm\DuoSecurity\Query;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use JsonException;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\DuoApiException;
use spkm\DuoSecurity\DuoConnector;
use spkm\DuoSecurity\DuoPaginator;
use spkm\DuoSecurity\Exceptions\ServerErrorException;
use spkm\DuoSecurity\Requests\ListRequest;
use spkm\DuoSecurity\Support\Envelope;

/**
 * A query over one Duo list, from a resource's list method: DuoSecurity::users()->listUsers(). Duo's lists take
 * no filter beyond the few named parameters each documents (which the list method takes), so the query only
 * pages: get() for one page, first() for its first record, all() or lazy() for every record, paginate() for the
 * pages themselves, and count() for how many there are in all.
 *
 * @template TData of Data
 */
class ListQuery
{
    /** @var array<string, mixed> */
    protected array $params = [];

    /**
     * @param  class-string<TData>|null  $dataClass  The model records are returned as; null for arrays (or strings).
     * @param  Closure(array<string, mixed>): ListRequest  $request  Builds the list request for the parameters.
     */
    public function __construct(protected DuoConnector $connector, protected ?string $dataClass, protected Closure $request) {}

    /**
     * Another query parameter the endpoint documents.
     */
    public function withParameter(string $key, mixed $value): static
    {
        $query = clone $this;
        $query->params[$key] = $value;

        return $query;
    }

    /**
     * How many records a page holds (get()), or at most how many are fetched at once (all(), lazy()), up to what
     * the endpoint allows.
     */
    public function limit(int $limit): static
    {
        return $this->withParameter('limit', max(1, $limit));
    }

    /**
     * Where a page starts: the offset of its first record.
     */
    public function skip(int $offset): static
    {
        return $this->withParameter('offset', max(0, $offset));
    }

    /**
     * The request for one page, as the query stands.
     */
    public function toRequest(): ListRequest
    {
        return ($this->request)($this->params);
    }

    /**
     * One page of records: the first, or the one skip() and limit() describe.
     *
     * @return Collection<int, TData|array<string, mixed>|string>
     */
    public function get(): Collection
    {
        $response = $this->connector->send($this->toRequest());

        if (! $response->successful()) {
            throw DuoApiException::fromResponse($response);
        }

        try {
            $items = $response->dto();
        } catch (JsonException) {
            throw new ServerErrorException($response);
        }

        return $items instanceof Collection ? $items : collect();
    }

    /**
     * The first record, or null when there is none.
     *
     * @return TData|array<string, mixed>|string|null
     */
    public function first(): mixed
    {
        return $this->limit(1)->get()->first();
    }

    /**
     * Every record, fetched a page at a time as they are read.
     *
     * @return LazyCollection<int, TData|array<string, mixed>|string>
     */
    public function lazy(): LazyCollection
    {
        $paginator = $this->paginate();

        return $this->dataClass === null ? $paginator->collect() : $paginator->models($this->dataClass);
    }

    /**
     * Every record.
     *
     * @return Collection<int, TData|array<string, mixed>|string>
     */
    public function all(): Collection
    {
        return $this->lazy()->collect();
    }

    /**
     * The pages, $perPage records each (limit() or duo-security.pagination.per_page by default).
     */
    public function paginate(?int $perPage = null): DuoPaginator
    {
        $perPage ??= is_int($this->params['limit'] ?? null) ? $this->params['limit'] : null;
        $paginator = $this->connector->paginate($this->toRequest(), $perPage);

        return $paginator instanceof DuoPaginator ? $paginator : new DuoPaginator($this->connector, $this->toRequest(), $perPage);
    }

    /**
     * How many records there are in all, from the total Duo gives with a page.
     */
    public function count(): int
    {
        $response = $this->connector->send($this->limit(1)->toRequest());

        if (! $response->successful()) {
            throw DuoApiException::fromResponse($response);
        }

        try {
            return Envelope::total($response) ?? count(Envelope::items($response, $this->toRequest()->itemsKey()));
        } catch (JsonException) {
            throw new ServerErrorException($response);
        }
    }
}
