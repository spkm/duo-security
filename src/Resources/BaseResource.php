<?php

namespace spkm\DuoSecurity\Resources;

use Closure;
use JsonException;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\DuoApiException;
use spkm\DuoSecurity\DuoConnector;
use spkm\DuoSecurity\Exceptions\NotFoundException;
use spkm\DuoSecurity\Exceptions\ServerErrorException;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Query\LogQuery;
use spkm\DuoSecurity\Requests\DuoRequest;
use spkm\DuoSecurity\Requests\ListRequest;
use spkm\DuoSecurity\Requests\LogRequest;

/**
 * What every resource shares: its connector, and sending a request for its model.
 */
abstract class BaseResource
{
    public function __construct(protected DuoConnector $connector) {}

    public function connector(): DuoConnector
    {
        return $this->connector;
    }

    /**
     * Send a request and return what Duo answered as the request's model (or models, arrays, strings or null, by
     * the request's shape), throwing for an error.
     */
    protected function fetch(DuoRequest $request): mixed
    {
        $response = $this->connector->send($request);

        if (! $response->successful()) {
            throw DuoApiException::fromResponse($response);
        }

        try {
            return $response->dto();
        } catch (JsonException) {
            throw new ServerErrorException($response);
        }
    }

    /**
     * As fetch(), but null when Duo has no such record.
     */
    protected function fetchOrNull(DuoRequest $request): mixed
    {
        try {
            return $this->fetch($request);
        } catch (NotFoundException) {
            return null;
        }
    }

    /**
     * As fetch(), for an operation whose answer is nothing: true once Duo said it succeeded.
     */
    protected function perform(DuoRequest $request): bool
    {
        $this->fetch($request);

        return true;
    }

    /**
     * A query over a list: get() for one page, all() or lazy() for every record, paginate() for the pages.
     *
     * @template TData of Data
     *
     * @param  class-string<TData>|null  $dataClass
     * @param  Closure(array<string, mixed>): ListRequest  $request
     * @return ListQuery<TData>
     */
    protected function list(Closure $request, ?string $dataClass = null): ListQuery
    {
        return new ListQuery($this->connector, $dataClass, $request);
    }

    /**
     * A query over a log: between() for the window, then get(), lazy() or paginate().
     *
     * @template TData of Data
     *
     * @param  class-string<TData>|null  $dataClass
     * @param  Closure(array<string, mixed>): LogRequest  $request
     * @return LogQuery<TData>
     */
    protected function logs(Closure $request, ?string $dataClass = null): LogQuery
    {
        return new LogQuery($this->connector, $dataClass, $request);
    }
}
