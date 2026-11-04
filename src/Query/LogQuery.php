<?php

namespace spkm\DuoSecurity\Query;

use Closure;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use JsonException;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\DuoApiException;
use spkm\DuoSecurity\DuoConnector;
use spkm\DuoSecurity\DuoPaginator;
use spkm\DuoSecurity\Exceptions\ServerErrorException;
use spkm\DuoSecurity\Requests\LogRequest;

/**
 * A query over one of Duo's logs, from DuoSecurity::logs(): the window (between(), since()), the order (sort()),
 * the endpoint's filters (withParameter()), then get() for one page, lazy() or all() for every event, or
 * paginate() for the pages. Duo holds the latest two minutes back, so since() ends two minutes ago unless told
 * otherwise.
 *
 * @template TData of Data
 */
class LogQuery
{
    /**
     * How long Duo takes to make an event available.
     */
    public const DELAY_SECONDS = 120;

    /** @var array<string, mixed> */
    protected array $params = [];

    /**
     * @param  class-string<TData>|null  $dataClass  The model events are returned as; null for arrays.
     * @param  Closure(array<string, mixed>): LogRequest  $request  Builds the log request for the parameters.
     */
    public function __construct(protected DuoConnector $connector, protected ?string $dataClass, protected Closure $request) {}

    /**
     * The window: events from $from to $to (a date, or a timestamp in the endpoint's unit).
     */
    public function between(DateTimeInterface|int $from, DateTimeInterface|int $to): static
    {
        return $this->withParameter('mintime', $from)->withParameter('maxtime', $to);
    }

    /**
     * Events from $from until two minutes ago, the latest Duo has made available.
     */
    public function since(DateTimeInterface|int $from): static
    {
        return $this->between($from, Carbon::now()->subSeconds(self::DELAY_SECONDS));
    }

    /**
     * The order: ts:desc (newest first, Duo's default) or ts:asc.
     */
    public function sort(string $sort): static
    {
        return $this->withParameter('sort', $sort);
    }

    public function oldestFirst(): static
    {
        return $this->sort('ts:asc');
    }

    public function newestFirst(): static
    {
        return $this->sort('ts:desc');
    }

    /**
     * How many events a page holds, up to what the endpoint allows (1000).
     */
    public function limit(int $limit): static
    {
        return $this->withParameter('limit', max(1, $limit));
    }

    /**
     * A filter the endpoint documents (users, applications, results, factors, event_types...).
     */
    public function withParameter(string $key, mixed $value): static
    {
        $query = clone $this;
        $query->params[$key] = $value;

        return $query;
    }

    /**
     * The request for one page, as the query stands.
     */
    public function toRequest(): LogRequest
    {
        return ($this->request)($this->params);
    }

    /**
     * One page of events.
     *
     * @return Collection<int, TData|array<string, mixed>>
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
     * Every event in the window, fetched a page at a time as they are read.
     *
     * @return LazyCollection<int, TData|array<string, mixed>>
     */
    public function lazy(): LazyCollection
    {
        $paginator = $this->paginate();

        return $this->dataClass === null ? $paginator->collect() : $paginator->models($this->dataClass);
    }

    /**
     * Every event in the window.
     *
     * @return Collection<int, TData|array<string, mixed>>
     */
    public function all(): Collection
    {
        return $this->lazy()->collect();
    }

    /**
     * The pages, $perPage events each (limit(), or the most the endpoint allows).
     */
    public function paginate(?int $perPage = null): DuoPaginator
    {
        $perPage ??= is_int($this->params['limit'] ?? null) ? $this->params['limit'] : null;
        $paginator = $this->connector->paginate($this->toRequest(), $perPage);

        return $paginator instanceof DuoPaginator ? $paginator : new DuoPaginator($this->connector, $this->toRequest(), $perPage);
    }
}
