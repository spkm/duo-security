<?php

namespace spkm\DuoSecurity\Requests;

use Saloon\Enums\Method;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\DuoSecurity\Support\Payload;

/**
 * A GET for a log: events between mintime and maxtime, newest first unless sorted otherwise. The v2 logs
 * (authentication, authorization, activity, telephony) take their times in milliseconds, nest their events under a
 * key of their own and page by a next_offset cursor; the v1 logs (administrator, offline enrollment) take mintime
 * in seconds and answer the 1000 earliest events after it, so the next page starts after the last event's time.
 * Duo keeps 180 days, holds the latest two minutes back, and asks to be polled no more than once a minute.
 */
abstract class LogRequest extends DuoRequest implements Paginatable
{
    /** Paged by the next_offset cursor Duo gives with each page. */
    public const BY_CURSOR = 'next_offset';

    /** Paged by asking again from the time of the last event. */
    public const BY_TIME = 'mintime';

    protected Method $method = Method::GET;

    protected string $responseShape = self::LIST;

    /**
     * The most events the endpoint returns at once.
     */
    protected int $maxPageSize = 1000;

    protected string $pagedBy = self::BY_CURSOR;

    /**
     * Whether mintime and maxtime are Unix timestamps in milliseconds (the v2 logs) rather than seconds.
     */
    protected bool $millisecondTimes = true;

    /** @var array<string, mixed> */
    protected array $params = [];

    /**
     * @param  array<string, mixed>  $params  mintime and maxtime (as dates, or timestamps in the endpoint's unit), limit, sort, next_offset, and the endpoint's filters.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function maxPageSize(): int
    {
        return $this->maxPageSize;
    }

    public function pagedBy(): string
    {
        return $this->pagedBy;
    }

    public function timesInMilliseconds(): bool
    {
        return $this->millisecondTimes;
    }

    /**
     * Where the window ends for a log paged by time (which Duo gives no maxtime for): the maxtime asked for, as a
     * Unix timestamp in the endpoint's unit, or null for no end.
     */
    public function windowEnd(): ?int
    {
        $end = $this->params['maxtime'] ?? null;

        return $end === null ? null : Payload::timestamp($end, $this->millisecondTimes);
    }

    /**
     * The query Duo documents: for a log paged by time, mintime alone (it answers the 1000 earliest events after it,
     * so maxtime and limit are the paginator's to enforce).
     *
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        $params = $this->params;

        if ($this->pagedBy === self::BY_TIME) {
            unset($params['maxtime'], $params['limit']);
        }

        return Payload::parameters($params, $this->millisecondTimes ? ['mintime', 'maxtime'] : []);
    }
}
