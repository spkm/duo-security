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
        if (trim($value) === '' || $value === '.' || $value === '..') {
            throw new InvalidArgumentException('"'.$value.'" cannot be used in a request path.');
        }

        return rawurlencode($value);
    }

    /**
     * A path given by the caller, as DuoSecurity::get('/admin/v1/users') takes one: from the root of the API
     * hostname, each segment checked, and a query string left alone for the connector to merge.
     */
    public static function api(string $path): string
    {
        [$route, $query] = array_pad(explode('?', $path, 2), 2, null);
        $segments = explode('/', trim($route, '/'));

        foreach ($segments as $segment) {
            if ($segment === '.' || $segment === '..') {
                throw new InvalidArgumentException('"'.$path.'" is not a path Duo serves.');
            }
        }

        return '/'.implode('/', $segments).($query === null ? '' : '?'.$query);
    }
}
