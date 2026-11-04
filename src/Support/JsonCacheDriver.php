<?php

namespace spkm\DuoSecurity\Support;

use DateTimeImmutable;
use Illuminate\Contracts\Cache\Repository;
use JsonException;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Data\CachedResponse;
use Saloon\Data\RecordedResponse;

/**
 * Keeps cached answers in a Laravel cache store as JSON (the status, headers and body) rather than as serialised
 * PHP objects, so that nothing in the store is ever unserialised: whoever can write to a shared cache can't put a
 * PHP object into the application through it.
 */
final class JsonCacheDriver implements Driver
{
    public function __construct(private readonly Repository $store) {}

    public function set(string $key, CachedResponse $cachedResponse): void
    {
        $this->store->put($key, json_encode([
            'expires_at' => $cachedResponse->expiresAt->getTimestamp(),
            'ttl' => $cachedResponse->ttl,
            'response' => $cachedResponse->recordedResponse->toFile(),
        ], JSON_THROW_ON_ERROR), $cachedResponse->ttl);
    }

    public function get(string $cacheKey): ?CachedResponse
    {
        $stored = $this->store->get($cacheKey);

        if (! is_string($stored)) {
            return null;
        }

        try {
            $entry = json_decode($stored, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }

        if (! is_array($entry) || ! is_int($entry['expires_at'] ?? null) || ! is_int($entry['ttl'] ?? null) || ! is_string($entry['response'] ?? null)) {
            return null;
        }

        $cached = new CachedResponse(RecordedResponse::fromFile($entry['response']), (new DateTimeImmutable)->setTimestamp($entry['expires_at']), $entry['ttl']);

        return $cached->hasExpired() ? null : $cached;
    }

    public function delete(string $cacheKey): void
    {
        $this->store->forget($cacheKey);
    }
}
