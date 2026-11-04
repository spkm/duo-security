<?php

namespace spkm\DuoSecurity\Support;

use JsonException;
use Saloon\Http\Response;

/**
 * Reads what Duo answered. Every JSON answer is an envelope: {"stat": "OK", "response": ...} when the call
 * succeeded, where the response is an object, a list, a string or nothing; {"stat": "FAIL", "code": 40002,
 * "message": "...", "message_detail": "..."} when it failed, with the HTTP status as the first three digits of the
 * code. A page of a list carries "metadata" beside the response ({"total_objects", "next_offset", "prev_offset"}),
 * or inside it for the v2 logs, whose records sit under a key of their own ("authlogs", "items", "events").
 */
final class Envelope
{
    /**
     * The keys the v2 log endpoints keep their records under, tried in turn when a request names none.
     */
    public const ITEM_KEYS = ['authlogs', 'items', 'events'];

    /**
     * The decoded envelope.
     *
     * @return array<string, mixed>
     *
     * @throws JsonException When the body isn't a JSON object.
     */
    public static function decode(Response $response): array
    {
        $decoded = json_decode($response->body(), true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($decoded)) {
            throw new JsonException('Duo answered with JSON that is not an object.');
        }

        return $decoded;
    }

    /**
     * Whether Duo said the call succeeded: a 2xx status and a stat of OK.
     */
    public static function succeeded(Response $response): bool
    {
        if (! $response->successful()) {
            return false;
        }

        try {
            return (self::decode($response)['stat'] ?? null) === 'OK';
        } catch (JsonException) {
            return false;
        }
    }

    /**
     * What Duo answered with: the envelope's response, whatever its type; null when there is none.
     *
     * @throws JsonException
     */
    public static function response(Response $response): mixed
    {
        return self::decode($response)['response'] ?? null;
    }

    /**
     * The records in an answer: the response when it is a list; the list under $key (or the first of ITEM_KEYS)
     * when it is an object holding one; else the one object. A record is an array, or a scalar for a list of
     * strings (bypass codes).
     *
     * @return list<mixed>
     *
     * @throws JsonException
     */
    public static function items(Response $response, ?string $key = null): array
    {
        $payload = self::response($response);

        if ($payload === null || $payload === '') {
            return [];
        }

        if (is_array($payload) && ! array_is_list($payload)) {
            foreach ($key === null ? self::ITEM_KEYS : [$key] as $candidate) {
                if (is_array($payload[$candidate] ?? null) && array_is_list($payload[$candidate])) {
                    return $payload[$candidate];
                }
            }

            return [$payload];
        }

        return is_array($payload) ? $payload : [$payload];
    }

    /**
     * A page's metadata: beside the response, or inside it for the v2 logs; empty when there is none.
     *
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    public static function metadata(Response $response): array
    {
        $decoded = self::decode($response);

        if (is_array($decoded['metadata'] ?? null)) {
            return $decoded['metadata'];
        }

        $payload = $decoded['response'] ?? null;

        return is_array($payload) && is_array($payload['metadata'] ?? null) ? $payload['metadata'] : [];
    }

    /**
     * Where the next page starts, or null on the last page: an offset for a list, a "timestamp,txid" cursor for
     * the v2 logs (which Duo has also written as a two-element list).
     *
     * @throws JsonException
     */
    public static function nextOffset(Response $response): int|string|null
    {
        $next = self::metadata($response)['next_offset'] ?? null;

        return match (true) {
            $next === null, $next === '' => null,
            is_array($next) => implode(',', array_map(fn (mixed $part) => is_scalar($part) ? (string) $part : '', $next)),
            is_int($next) => $next,
            is_string($next) && ctype_digit($next) => (int) $next,
            is_scalar($next) => (string) $next,
            default => null,
        };
    }

    /**
     * How many records match in all, from the page's total_objects; null when Duo didn't say.
     *
     * @throws JsonException
     */
    public static function total(Response $response): ?int
    {
        $total = self::metadata($response)['total_objects'] ?? null;

        return is_numeric($total) ? (int) $total : null;
    }

    /**
     * Duo's error code (40002), which carries the HTTP status in its first three digits; null when the body
     * isn't a FAIL envelope.
     */
    public static function code(Response $response): ?int
    {
        $code = self::field($response, 'code');

        return is_numeric($code) ? (int) $code : null;
    }

    public static function message(Response $response): ?string
    {
        $message = self::field($response, 'message');

        return is_scalar($message) && trim((string) $message) !== '' ? (string) $message : null;
    }

    public static function messageDetail(Response $response): ?string
    {
        $detail = self::field($response, 'message_detail');

        return is_scalar($detail) && trim((string) $detail) !== '' ? (string) $detail : null;
    }

    private static function field(Response $response, string $key): mixed
    {
        try {
            return self::decode($response)[$key] ?? null;
        } catch (JsonException) {
            return null;
        }
    }
}
