<?php

namespace spkm\DuoSecurity\Signing;

use BackedEnum;
use InvalidArgumentException;
use Stringable;

/**
 * Duo's request signature, version 5 (https://duo.com/docs/adminapi#authentication, and the same section of
 * https://duo.com/docs/authapi): every request carries a Date header and an Authorization header of HTTP basic
 * auth whose username is the application's integration key and whose password is an HMAC-SHA512, keyed with the
 * secret key, of a canonical description of the request. The canonical string is seven lines joined by "\n":
 *
 *   1. the Date header, RFC 2822 (Tue, 21 Aug 2012 17:29:18 -0000);
 *   2. the HTTP method, uppercase;
 *   3. the API hostname, lowercase;
 *   4. the path (/admin/v1/users);
 *   5. the query string: key=value pairs sorted by key, joined by &, RFC 3986 percent-encoded with uppercase hex
 *      and the unreserved characters (A-Z a-z 0-9 - _ . ~) left as they are; blank when there are none;
 *   6. the SHA-512 of the body, lowercase hex: of the JSON body for POST, PUT and PATCH ({} when the request has
 *      no parameters), of the empty string for GET and DELETE, which carry their parameters in the query string;
 *   7. the SHA-512 of the X-Duo-* headers, lowercase hex: names lowercased and sorted, values trimmed, all joined
 *      by NUL bytes (name1\0value1\0name2\0value2); of the empty string when there are none.
 *
 * Duo verifies the signature against the bytes it received, so the query string and the body that are signed must
 * be exactly what is sent: DuoAuthenticator signs the PSR-7 request once it is built, and the connector writes the
 * query string in this canonical form. The Duo HMAC Debugger (https://duosecurity.github.io/duo_hmac_debugger/)
 * shows what Duo computes for a request when a signature is refused (HTTP 401, code 40103). Should Duo publish a
 * new signing version, raise VERSION, change the lines here, and add its test vectors to
 * tests/Fixtures/signing-vectors.json beside the current ones.
 *
 * The parameter encoding and the X-Duo-* header rules follow Duo's own duo_api_php client (Copyright (c) 2022
 * Cisco Systems, Inc. and/or its affiliates, BSD 3-Clause: see LICENSE-duo_api_php).
 */
final class DuoSigner
{
    /**
     * The version of Duo's signing specification this implements.
     */
    public const VERSION = 5;

    /**
     * The SHA-512 of the empty string: the body line of a GET or DELETE, and the headers line when there are no
     * X-Duo-* headers.
     */
    public const EMPTY_HASH = 'cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e';

    /**
     * The canonical string for a request, as described above.
     *
     * @param  string  $query  The query string as sent, in the canonical form canonicalQuery() writes.
     * @param  string  $body  The body as sent ('' for GET and DELETE).
     * @param  array<string, string>  $xDuoHeaders  The X-Duo-* headers sent, by name.
     */
    public static function canonical(string $date, string $method, string $host, string $path, string $query, string $body, array $xDuoHeaders = []): string
    {
        return implode("\n", [
            $date,
            strtoupper($method),
            strtolower($host),
            $path,
            $query,
            hash('sha512', $body),
            self::headersHash($xDuoHeaders),
        ]);
    }

    /**
     * The signature of a canonical string: its HMAC-SHA512 with the secret key, as lowercase hex.
     */
    public static function signature(#[\SensitiveParameter] string $secretKey, string $canonical): string
    {
        return hash_hmac('sha512', $canonical, $secretKey);
    }

    /**
     * The Authorization header: HTTP basic auth with the integration key and the signature.
     */
    public static function authorization(string $integrationKey, string $signature): string
    {
        return 'Basic '.base64_encode($integrationKey.':'.$signature);
    }

    /**
     * The Authorization header for a request, from its credentials and the parts of it that are signed.
     *
     * @param  array<string, string>  $xDuoHeaders
     */
    public static function sign(Credentials $credentials, string $date, string $method, string $path, string $query, string $body, array $xDuoHeaders = []): string
    {
        $canonical = self::canonical($date, $method, $credentials->apiHostname, $path, $query, $body, $xDuoHeaders);

        return self::authorization($credentials->integrationKey, self::signature($credentials->secretKey(), $canonical));
    }

    /**
     * The query string Duo signs and reads: the parameters sorted by key (byte by byte, as Duo's own clients sort
     * them), each key=value percent-encoded as RFC 3986 says, joined by &. A null is left out, a boolean is sent as
     * true or false, an enum as its value. A list is refused: Duo has no parameter that repeats, and the parameters
     * it reads as lists (user_id_list, username_list and the like) are JSON strings, which the requests that take
     * them encode.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function canonicalQuery(array $parameters): string
    {
        ksort($parameters, SORT_STRING);
        $pairs = [];

        foreach ($parameters as $key => $value) {
            $value = self::scalar((string) $key, $value);

            if ($value !== null) {
                $pairs[] = rawurlencode((string) $key).'='.rawurlencode($value);
            }
        }

        return implode('&', $pairs);
    }

    /**
     * The hash of the X-Duo-* headers a request carries, for the seventh line: '' when there are none.
     *
     * @param  array<string, string>  $headers
     *
     * @throws InvalidArgumentException For a header that isn't an X-Duo-* one, that has a NUL byte in its name or
     *                                  value, or that is given twice (in any case).
     */
    public static function headersHash(array $headers): string
    {
        $canonical = [];

        foreach ($headers as $name => $value) {
            $name = strtolower(trim((string) $name));
            $value = trim((string) $value);

            if (! str_starts_with($name, 'x-duo-')) {
                throw new InvalidArgumentException('Only X-Duo-* headers can be signed; "'.$name.'" cannot.');
            }

            if (str_contains($name, "\0") || str_contains($value, "\0")) {
                throw new InvalidArgumentException('A signed header cannot carry a NUL byte in its name or value.');
            }

            if (array_key_exists($name, $canonical)) {
                throw new InvalidArgumentException('The header "'.$name.'" is given twice.');
            }

            $canonical[$name] = $value;
        }

        if ($canonical === []) {
            return self::EMPTY_HASH;
        }

        ksort($canonical, SORT_STRING);
        $parts = [];

        foreach ($canonical as $name => $value) {
            $parts[] = $name;
            $parts[] = $value;
        }

        return hash('sha512', implode("\0", $parts));
    }

    /**
     * A parameter's value as the string Duo reads, or null to leave it out.
     */
    private static function scalar(string $key, mixed $value): ?string
    {
        return match (true) {
            $value === null => null,
            is_bool($value) => $value ? 'true' : 'false',
            $value instanceof BackedEnum => (string) $value->value,
            is_scalar($value), $value instanceof Stringable => (string) $value,
            is_array($value) => throw new InvalidArgumentException('The parameter "'.$key.'" is a list, which Duo doesn\'t read from a query string; send it as a JSON string.'),
            default => throw new InvalidArgumentException('The parameter "'.$key.'" cannot be written into a query string.'),
        };
    }
}
