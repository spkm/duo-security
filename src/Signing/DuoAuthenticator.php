<?php

namespace spkm\DuoSecurity\Signing;

use Illuminate\Support\Carbon;
use Psr\Http\Message\RequestInterface;
use Saloon\Contracts\Authenticator;
use Saloon\Http\PendingRequest;

/**
 * Signs requests with one application's credentials, as DuoSigner describes. Saloon applies an authenticator
 * before the query string and the body are written, so set() only dates the request; the connector has sign()
 * compute the Authorization header once the PSR-7 request is built, from the very bytes that are sent.
 */
final class DuoAuthenticator implements Authenticator
{
    public function __construct(private readonly Credentials $credentials) {}

    public function credentials(): Credentials
    {
        return $this->credentials;
    }

    /**
     * Dates the request: the Date header is the first line of what is signed.
     */
    public function set(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add('Date', self::date());
    }

    /**
     * The request with its Authorization header, computed from its method, path, query string, body and X-Duo-*
     * headers as they will be sent. A request without a Date header (one sent without set()) is dated first.
     */
    public function sign(RequestInterface $request): RequestInterface
    {
        if ($request->getHeaderLine('Date') === '') {
            $request = $request->withHeader('Date', self::date());
        }

        $uri = $request->getUri();
        $stream = $request->getBody();
        $body = (string) $stream;

        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        $xDuoHeaders = [];

        foreach (array_keys($request->getHeaders()) as $name) {
            if (str_starts_with(strtolower($name), 'x-duo-')) {
                $xDuoHeaders[$name] = $request->getHeaderLine($name);
            }
        }

        $authorization = DuoSigner::sign(
            $this->credentials,
            $request->getHeaderLine('Date'),
            $request->getMethod(),
            $uri->getPath() === '' ? '/' : $uri->getPath(),
            $uri->getQuery(),
            $body,
            $xDuoHeaders,
        );

        return $request->withHeader('Authorization', $authorization);
    }

    /**
     * Now, as RFC 2822 writes it (Tue, 21 Aug 2012 17:29:18 +0000), in UTC. Duo refuses a request dated more than
     * five minutes from its own clock.
     */
    public static function date(): string
    {
        return Carbon::now('UTC')->format(DATE_RFC2822);
    }
}
