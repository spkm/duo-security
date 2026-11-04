<?php

namespace spkm\DuoSecurity\Tests\Helpers;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use spkm\DuoSecurity\Admin\AdminConnector;
use spkm\DuoSecurity\Auth\AuthConnector;
use spkm\DuoSecurity\Signing\Credentials;
use spkm\DuoSecurity\Signing\DuoSigner;

class FakeDuo
{
    public const HOST = 'api-example.duosecurity.test';

    public const BASE_URL = 'https://api-example.duosecurity.test';

    public const INTEGRATION_KEY = 'DIWJ8X6AEYOR5OMC6TQ1';

    public const SECRET_KEY = 'Zh5eGmUq9zpfQnyUIu5OL9iWoMMv5ZNmk3zLJ4Ep';

    /**
     * Fake Duo: each request class (or URL pattern) answers with the given response.
     *
     * @param  array<class-string<Request>|string, MockResponse|callable(PendingRequest): MockResponse>  $responses
     */
    public static function respondWith(array $responses = []): MockClient
    {
        MockClient::destroyGlobal();

        return MockClient::global($responses);
    }

    public static function credentials(string $host = self::HOST): Credentials
    {
        return new Credentials(self::INTEGRATION_KEY, self::SECRET_KEY, $host);
    }

    /**
     * An Admin API connector for the example account.
     */
    public static function admin(string $host = self::HOST): AdminConnector
    {
        return new AdminConnector(self::credentials($host));
    }

    /**
     * An Auth API connector for the example account.
     */
    public static function auth(string $host = self::HOST): AuthConnector
    {
        return new AuthConnector(self::credentials($host));
    }

    /**
     * Duo's answer to a call that succeeded: {"stat": "OK", "response": ...}, with metadata beside it when given.
     *
     * @param  array<string, mixed>|null  $metadata
     */
    public static function ok(mixed $response = '', ?array $metadata = null, int $status = 200): MockResponse
    {
        $body = ['stat' => 'OK', 'response' => $response];

        if ($metadata !== null) {
            $body['metadata'] = $metadata;
        }

        return MockResponse::make($body, $status, ['Content-Type' => 'application/json']);
    }

    /**
     * Duo's answer to a call that failed: {"stat": "FAIL", "code": 40002, "message": ..., "message_detail": ...},
     * with the HTTP status the code's first three digits give unless told otherwise.
     */
    public static function fail(int $code, string $message = 'Invalid request parameters', ?string $detail = null, ?int $status = null): MockResponse
    {
        $body = ['stat' => 'FAIL', 'code' => $code, 'message' => $message];

        if ($detail !== null) {
            $body['message_detail'] = $detail;
        }

        return MockResponse::make($body, $status ?? intdiv($code, 100), ['Content-Type' => 'application/json']);
    }

    /**
     * A page of a list, as Duo sends one: the records, with total_objects and (while more follow) next_offset in
     * the metadata beside them.
     *
     * @param  list<mixed>  $items
     */
    public static function page(array $items, int|string|null $nextOffset = null, ?int $total = null): MockResponse
    {
        $metadata = ['total_objects' => $total ?? count($items)];

        if ($nextOffset !== null) {
            $metadata['next_offset'] = $nextOffset;
        }

        return self::ok($items, $metadata);
    }

    /**
     * A page of a v2 log, as Duo sends one: the events under their key, with the metadata inside the response.
     *
     * @param  list<mixed>  $items
     */
    public static function logPage(string $key, array $items, int|string|array|null $nextOffset = null, ?int $total = null): MockResponse
    {
        $metadata = ['total_objects' => $total ?? count($items)];

        if ($nextOffset !== null) {
            $metadata['next_offset'] = $nextOffset;
        }

        return self::ok([$key => $items, 'metadata' => $metadata]);
    }

    /**
     * A faked list of $total users that answers each page by its limit and offset, as Duo does: the records, with
     * total_objects and (while more follow) next_offset in the metadata.
     *
     * @return callable(PendingRequest): MockResponse
     */
    public static function listPages(int $total): callable
    {
        return function (PendingRequest $request) use ($total): MockResponse {
            $offset = (int) $request->query()->get('offset', 0);
            $limit = (int) $request->query()->get('limit', 100);
            $records = [];

            for ($id = $offset + 1; $id <= min($total, $offset + $limit); $id++) {
                $records[] = ['user_id' => "DU$id", 'username' => "person$id"];
            }

            return self::page($records, $offset + $limit < $total ? $offset + $limit : null, $total);
        };
    }

    /**
     * Send one request through the Admin API connector to a faked Duo that answers it with the given response: a
     * MockResponse as it is, or anything else wrapped in an OK envelope.
     */
    public static function send(Request $request, mixed $response = '', int $status = 200): Response
    {
        self::respondWith([$request::class => $response instanceof MockResponse ? $response : self::ok($response, status: $status)]);

        return self::admin()->send($request);
    }

    /**
     * The Authorization header a request should have carried, computed afresh from the bytes Duo received.
     */
    public static function signatureFor(Response $response, ?Credentials $credentials = null): string
    {
        $psr = $response->getPsrRequest();
        $headers = [];

        foreach (array_keys($psr->getHeaders()) as $name) {
            if (str_starts_with(strtolower($name), 'x-duo-')) {
                $headers[$name] = $psr->getHeaderLine($name);
            }
        }

        return DuoSigner::sign(
            $credentials ?? self::credentials(),
            $psr->getHeaderLine('Date'),
            $psr->getMethod(),
            $psr->getUri()->getPath(),
            $psr->getUri()->getQuery(),
            (string) $psr->getBody(),
            $headers,
        );
    }

    /**
     * The request Duo received for the given request class, as Saloon built it.
     *
     * @param  class-string<Request>  $requestClass
     */
    public static function received(MockClient $mockClient, string $requestClass): PendingRequest
    {
        return $mockClient->findResponseByRequest($requestClass)->getPendingRequest();
    }

    /**
     * The responses Duo gave to every request of the given class, in the order they were sent.
     *
     * @param  class-string<Request>  $requestClass
     * @return list<Response>
     */
    public static function responsesTo(MockClient $mockClient, string $requestClass): array
    {
        return collect($mockClient->getRecordedResponses())
            ->filter(fn (Response $response) => $response->getRequest() instanceof $requestClass)
            ->values()
            ->all();
    }

    /**
     * The query strings Duo received for every request of the given class, as sent on the wire.
     *
     * @param  class-string<Request>  $requestClass
     * @return list<string>
     */
    public static function queriesReceived(MockClient $mockClient, string $requestClass): array
    {
        return array_map(fn (Response $response) => $response->getPsrRequest()->getUri()->getQuery(), self::responsesTo($mockClient, $requestClass));
    }

    /**
     * The URIs Duo received for every request of the given class, as sent on the wire.
     *
     * @param  class-string<Request>  $requestClass
     * @return list<string>
     */
    public static function urisReceived(MockClient $mockClient, string $requestClass): array
    {
        return array_map(fn (Response $response) => (string) $response->getPsrRequest()->getUri(), self::responsesTo($mockClient, $requestClass));
    }

    /**
     * The bodies Duo received for every request of the given class, as sent on the wire.
     *
     * @param  class-string<Request>  $requestClass
     * @return list<string>
     */
    public static function bodiesReceived(MockClient $mockClient, string $requestClass): array
    {
        return array_map(fn (Response $response) => (string) $response->getPsrRequest()->getBody(), self::responsesTo($mockClient, $requestClass));
    }

    /**
     * A header Duo received with every request of the given class.
     *
     * @param  class-string<Request>  $requestClass
     * @return list<string>
     */
    public static function headersReceived(MockClient $mockClient, string $requestClass, string $header): array
    {
        return array_map(fn (Response $response) => $response->getPsrRequest()->getHeaderLine($header), self::responsesTo($mockClient, $requestClass));
    }

    /**
     * The Authorization header Duo received with every request of the given class.
     *
     * @param  class-string<Request>  $requestClass
     * @return list<string>
     */
    public static function authorizationsReceived(MockClient $mockClient, string $requestClass): array
    {
        return self::headersReceived($mockClient, $requestClass, 'Authorization');
    }
}
