<?php

namespace spkm\DuoSecurity\Support;

use InvalidArgumentException;

/**
 * Writes the parts of a request path safely: a value that goes in the path is encoded, and one that would change
 * which endpoint is called ('', '.' or '..', which the HTTP client would collapse into the parent path) is refused.
 */
final class Path
{
    /**
     * One segment of a path, from a value such as a user id, an integration key or a directory key.
     */
    public static function segment(string $value): string
    {
        if (trim($value) === '' || self::isDotSegment($value)) {
            throw new InvalidArgumentException('"'.$value.'" cannot be used in a request path.');
        }

        return rawurlencode($value);
    }

    /**
     * A path given by the caller, as DuoSecurity::get('/admin/v1/users/{id}', [], ['id' => $userId]) takes one:
     * from the root of the API hostname, each {placeholder} filled with its segment encoded (so an id can't carry
     * the path somewhere else), each segment checked, and a query string left alone for the connector to merge. A
     * path with a fragment, a second ?, an id written straight into it that holds one of those, or a dot segment
     * (plain or percent-encoded) is refused.
     *
     * @param  array<string, string|int>  $segments  The values for the path's {placeholders}.
     */
    public static function api(string $path, array $segments = []): string
    {
        if (str_contains($path, '#')) {
            throw new InvalidArgumentException('"'.$path.'" is not a path Duo serves: a path has no fragment.');
        }

        [$route, $query] = array_pad(explode('?', $path, 2), 2, null);

        if ($query !== null && str_contains($query, '?')) {
            throw new InvalidArgumentException('"'.$path.'" is not a path Duo serves: it has two query strings.');
        }

        $route = (string) preg_replace_callback('/\{([a-z_][a-z0-9_]*)\}/i', function (array $match) use ($segments, $path): string {
            $value = $segments[$match[1]] ?? throw new InvalidArgumentException('The path "'.$path.'" needs a value for {'.$match[1].'}.');

            return self::segment((string) $value);
        }, $route);

        $parts = explode('/', trim($route, '/'));

        foreach ($parts as $part) {
            if (self::isDotSegment($part) || self::isDotSegment(rawurldecode($part))) {
                throw new InvalidArgumentException('"'.$path.'" is not a path Duo serves: it climbs the path.');
            }
        }

        return '/'.implode('/', $parts).($query === null ? '' : '?'.$query);
    }

    private static function isDotSegment(string $value): bool
    {
        return $value === '.' || $value === '..';
    }
}
