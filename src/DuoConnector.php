<?php

namespace spkm\DuoSecurity;

use GuzzleHttp\Promise\PromiseInterface;
use JsonSerializable;
use LogicException;
use Psr\Http\Message\RequestInterface;
use Saloon\Config;
use Saloon\Contracts\Authenticator;
use Saloon\Enums\Method;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Connector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\Paginator;
use Saloon\Traits\Plugins\HasTimeout;
use spkm\DuoSecurity\Data\Data;
use spkm\DuoSecurity\Requests\AdhocBodyRequest;
use spkm\DuoSecurity\Requests\AdhocRequest;
use spkm\DuoSecurity\Requests\DuoRequest;
use spkm\DuoSecurity\Requests\LogRequest;
use spkm\DuoSecurity\Signing\Credentials;
use spkm\DuoSecurity\Signing\DuoAuthenticator;
use spkm\DuoSecurity\Signing\DuoSigner;
use spkm\DuoSecurity\Support\QueryString;

/**
 * The connection to one Duo application: the API hostname, the keys that sign every request, paging, and ad-hoc
 * calls for anything the resources don't cover. The Admin API and the Auth API each extend it with their own
 * resources.
 */
abstract class DuoConnector extends Connector implements HasPagination
{
    use HasTimeout;

    protected float $connectTimeout;

    protected float $requestTimeout;

    public function __construct(protected Credentials $credentials)
    {
        $this->connectTimeout = self::timeout(config('duo-security.timeouts.connect'), Config::$defaultConnectionTimeout);
        $this->requestTimeout = self::timeout(config('duo-security.timeouts.request'), Config::$defaultRequestTimeout);

        if (config('duo-security.retries.times')) {
            $this->tries = (int) config('duo-security.retries.times');
            $this->retryInterval = (int) config('duo-security.retries.interval_ms', 1000);
            $this->useExponentialBackoff = true;
            $this->throwOnMaxTries = false;
        }
    }

    /**
     * https://{api hostname}: every request carries its full path from there (/admin/v1/..., /auth/v2/...).
     */
    public function resolveBaseUrl(): string
    {
        return 'https://'.$this->credentials->apiHostname;
    }

    public function credentials(): Credentials
    {
        return $this->credentials;
    }

    /**
     * A fingerprint of the account and application, so that what one fetched is never served to another (the
     * response cache keys on it) without the keys being written anywhere.
     */
    public function credentialsFingerprint(): string
    {
        return $this->credentials->fingerprint();
    }

    /**
     * The namespace of the other API's requests, which this connector refuses: they would be signed with the
     * wrong application's keys and answered 403.
     */
    abstract protected function refusedRequests(): string;

    public function send(Request $request, ?MockClient $mockClient = null, ?callable $handleRetry = null): Response
    {
        $this->refuseForeign($request);

        return parent::send($request, $mockClient, $handleRetry);
    }

    public function sendAsync(Request $request, ?MockClient $mockClient = null): PromiseInterface
    {
        $this->refuseForeign($request);

        return parent::sendAsync($request, $mockClient);
    }

    protected function refuseForeign(Request $request): void
    {
        if (str_starts_with($request::class, $this->refusedRequests())) {
            $other = str_contains($this->refusedRequests(), '\\Auth\\') ? 'Auth API' : 'Admin API';
            $accessor = $other === 'Auth API' ? 'DuoSecurity::authConnector()->send() or DuoSecurity::auth()' : 'DuoSecurity::admin()->send() or DuoSecurity::{resource}()';

            throw new LogicException(class_basename($request).' is an '.$other.' request and cannot be sent with this connector\'s keys; send it with '.$accessor.'.');
        }
    }

    protected function defaultAuth(): ?Authenticator
    {
        return $this->credentials->authenticator();
    }

    /**
     * Writes the query string in the form Duo signs (keys sorted, RFC 3986 encoding), then signs the request from
     * the bytes about to be sent: Saloon applies the authenticator before the query string and the body are
     * written, so the Authorization header is computed here, last.
     */
    public function handlePsrRequest(RequestInterface $request, PendingRequest $pendingRequest): RequestInterface
    {
        $uri = $request->getUri();
        $parameters = [...QueryString::parse($uri->getQuery()), ...$pendingRequest->query()->all()];
        $request = $request->withUri($uri->withQuery(DuoSigner::canonicalQuery($parameters)));

        $authenticator = $pendingRequest->getAuthenticator();

        return $authenticator instanceof DuoAuthenticator ? $authenticator->sign($request) : $request;
    }

    /**
     * Retry a request that changes nothing when Duo can't be reached, asks for a pause (429) or fails on its side;
     * retry one that changes something only when it asked to be sent again after a dropped connection.
     */
    public function handleRetry(FatalRequestException|RequestException $exception, Request $request): bool
    {
        $isSafe = $request instanceof DuoRequest ? $request->isSafe() : $request->getMethod() === Method::GET;

        if ($exception instanceof FatalRequestException) {
            return $isSafe || ($request instanceof DuoRequest && $request->retriesDroppedConnections());
        }

        return $isSafe && (in_array($exception->getResponse()->status(), [408, 429], true) || $exception->getResponse()->serverError());
    }

    /**
     * Page through a list or a log, $perPage records at a time: duo-security.pagination.per_page by default for a
     * list, and as many as the endpoint allows (1000) for a log, where every page is a call against Duo's rate
     * limit.
     */
    public function paginate(Request $request, ?int $perPage = null): Paginator
    {
        if ($request instanceof LogRequest) {
            return $request->pagedBy() === LogRequest::BY_TIME
                ? new DuoTimestampPaginator($this, $request, $perPage)
                : new DuoLogPaginator($this, $request, $perPage);
        }

        return new DuoPaginator($this, $request, $perPage ?? (int) config('duo-security.pagination.per_page', 100));
    }

    /**
     * An ad-hoc GET by path, for an endpoint the package has no request for: DuoSecurity::get('/admin/v1/users/{id}',
     * ['limit' => 1], ['id' => $userId]). A value for the path goes in $segments, never written into the path.
     *
     * @param  array<string, mixed>  $query
     * @param  array<string, string|int>  $segments
     */
    public function get(string $path, array $query = [], array $segments = []): Response
    {
        return $this->send(new AdhocRequest(Method::GET, $path, $query, segments: $segments));
    }

    /**
     * @param  array<string, mixed>|Data|JsonSerializable  $body
     * @param  array<string, string|int>  $segments
     */
    public function post(string $path, array|Data|JsonSerializable $body = [], array $segments = []): Response
    {
        return $this->send(new AdhocBodyRequest(Method::POST, $path, $body, segments: $segments));
    }

    /**
     * @param  array<string, mixed>|Data|JsonSerializable  $body
     * @param  array<string, string|int>  $segments
     */
    public function put(string $path, array|Data|JsonSerializable $body = [], array $segments = []): Response
    {
        return $this->send(new AdhocBodyRequest(Method::PUT, $path, $body, segments: $segments));
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, string|int>  $segments
     */
    public function delete(string $path, array $query = [], array $segments = []): Response
    {
        return $this->send(new AdhocRequest(Method::DELETE, $path, $query, segments: $segments));
    }

    /**
     * A timeout from config: seconds, or the default when the value is empty or not positive (a zero would mean
     * waiting forever).
     */
    protected static function timeout(mixed $value, float $default): float
    {
        return is_numeric($value) && (float) $value > 0 ? (float) $value : $default;
    }

    /**
     * What a dump shows: the hostname and the integration key, never the secret key.
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return ['apiHostname' => $this->credentials->apiHostname, 'integrationKey' => $this->credentials->identity()];
    }

    /**
     * A connector holds the keys, so it is never written out with a queued job or a session; build one from config
     * where it is needed.
     *
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        throw new LogicException('A Duo connector cannot be serialised; resolve it from the DuoSecurity facade or config where it is used.');
    }

    /**
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }
}
