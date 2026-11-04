<?php

namespace spkm\DuoSecurity\Requests;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use JsonException;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\DuoApiException;
use spkm\DuoSecurity\DuoConnector;
use spkm\DuoSecurity\Exceptions\ServerErrorException;
use spkm\DuoSecurity\Support\Envelope;
use spkm\DuoSecurity\Support\QueryString;
use Throwable;

/**
 * What every request to Duo shares: the shape of the response inside Duo's envelope and the model it maps to (for
 * dto()), whether it can be retried, and optional response caching.
 */
abstract class DuoRequest extends Request implements Cacheable
{
    use HasCaching;

    /** One record: a model, or an array when the request has none. */
    public const ONE = 'one';

    /** A list of records, or a page of them: a Collection of models, of arrays, or of strings. */
    public const LIST = 'list';

    /** The response as decoded: an array, a string, whatever Duo answered. */
    public const RAW = 'raw';

    /** The body as a string, outside any envelope: a PNG logo. */
    public const TEXT = 'text';

    /** Nothing worth reading (Duo answers ""): dto() gives null. */
    public const NONE = 'none';

    protected string $responseShape = self::NONE;

    /** @var class-string<Data>|null */
    protected ?string $dto = null;

    /**
     * The key the records of a list sit under when Duo nests them inside the response (the v2 logs' authlogs,
     * items or events), or null when the response is the list.
     */
    protected ?string $itemsKey = null;

    protected bool $cacheable = false;

    protected bool $safe = true;

    protected bool $retryDroppedConnections = false;

    /**
     * Send the request again, as config('duo-security.retries') says, when the connection to Duo drops before it
     * answers, even though the request changes something: for a write the caller would rather repeat than lose.
     * A request that was received but not answered is sent twice, so use it where that is harmless (creating a
     * bypass code, say, which replaces the user's existing ones unless told otherwise).
     */
    public function retryingDroppedConnections(bool $retry = true): static
    {
        $this->retryDroppedConnections = $retry;

        return $this;
    }

    public function retriesDroppedConnections(): bool
    {
        return $this->retryDroppedConnections;
    }

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(Cache::store(config('duo-security.cache.store')));
    }

    public function cacheExpiryInSeconds(): int
    {
        return (int) config('duo-security.cache.seconds', 3600);
    }

    /**
     * @return array<Method>
     */
    protected function getCacheableMethods(): array
    {
        return $this->cacheable && config('duo-security.cache.enabled') ? [Method::GET] : [];
    }

    /**
     * One entry per account and application, so what one fetched is never served to another, and per query as it
     * is sent.
     */
    protected function cacheKey(PendingRequest $pendingRequest): string
    {
        $connector = $pendingRequest->getConnector();
        $identity = $connector instanceof DuoConnector ? $connector->credentialsFingerprint() : $connector::class;

        return 'duo-security.response.'.sha1($identity.' '.$pendingRequest->getMethod()->value.' '.$pendingRequest->getUrl().' '.QueryString::encode($pendingRequest->query()->all()));
    }

    /**
     * The exception for an error answer, so that $response->throw() (and the paginator, which calls it) gives
     * Duo's own exception classes rather than Saloon's.
     */
    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        return DuoApiException::fromResponse($response);
    }

    /**
     * Whether the request can be retried without a second thought: a GET that changes nothing.
     */
    public function isSafe(): bool
    {
        return $this->safe && $this->getMethod() === Method::GET;
    }

    /**
     * @return class-string<Data>|null
     */
    public function dtoClass(): ?string
    {
        return $this->dto;
    }

    public function responseShape(): string
    {
        return $this->responseShape;
    }

    public function itemsKey(): ?string
    {
        return $this->itemsKey;
    }

    /**
     * The response as the request's model (or models, or arrays or strings for a request without one; or the body
     * as a string for a file), null for an answer with nothing in it, or a DuoApiException for an error.
     */
    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw DuoApiException::fromResponse($response);
        }

        if ($this->responseShape === self::TEXT) {
            return $response->body();
        }

        try {
            if (! Envelope::succeeded($response)) {
                throw DuoApiException::fromResponse($response);
            }

            return match ($this->responseShape) {
                self::ONE => $this->one(Envelope::response($response)),
                self::LIST => $this->many(Envelope::items($response, $this->itemsKey)),
                self::RAW => Envelope::response($response),
                default => null,
            };
        } catch (JsonException) {
            // A successful status with something other than JSON, such as a proxy's or a maintenance page.
            throw new ServerErrorException($response);
        }
    }

    /**
     * The one record in a response: the object, or the first of a list (Duo answers some single records as a
     * one-element list), as the request's model when it has one.
     */
    protected function one(mixed $payload): mixed
    {
        if (is_array($payload) && array_is_list($payload)) {
            $payload = $payload[0] ?? null;
        }

        if (! is_array($payload)) {
            return null;
        }

        return $this->dto === null ? $payload : $this->dto::from($payload);
    }

    /**
     * @param  list<mixed>  $items
     * @return Collection<int, mixed>
     */
    protected function many(array $items): Collection
    {
        $dto = $this->dto;

        return collect($items)->map(fn (mixed $item) => $dto !== null && is_array($item) ? $dto::from($item) : $item);
    }
}
