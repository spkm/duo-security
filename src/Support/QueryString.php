<?php

namespace spkm\DuoSecurity\Support;

use spkm\DuoSecurity\Signing\DuoSigner;

/**
 * Query strings as Duo reads them: written in the canonical form that is signed (DuoSigner::canonicalQuery), and
 * read back from the form PHP's http_build_query writes (which Saloon uses, with + for a space).
 */
final class QueryString
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function encode(array $parameters): string
    {
        return DuoSigner::canonicalQuery($parameters);
    }

    /**
     * The parameters in a query string, decoded as a browser would (+ and %20 are both a space). A value keeps
     * any = it contains, and a key given twice keeps its last value, as Duo would read it.
     *
     * @return array<string, string>
     */
    public static function parse(string $query): array
    {
        $parameters = [];

        foreach (explode('&', $query) as $pair) {
            if ($pair === '') {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $pair, 2), 2, '');
            $parameters[urldecode($key)] = urldecode($value);
        }

        return $parameters;
    }
}
