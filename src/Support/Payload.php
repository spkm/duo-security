<?php

namespace spkm\DuoSecurity\Support;

use BackedEnum;
use DateTimeInterface;
use JsonSerializable;
use spkm\DuoSecurity\Data\Data;

/**
 * Writes what a request sends to Duo: its parameters as Duo reads them, and the lists and times some of them
 * take.
 */
final class Payload
{
    /**
     * The parameters Duo takes as a JSON string: user_id_list, username_list, group_id_list,
     * registered_device_key_list, the users of a bulk create and the operations of a bulk call. Duo's own clients
     * send them JSON-encoded, in a query string and in a JSON body alike.
     */
    public const JSON_PARAMETERS = ['user_id_list', 'username_list', 'group_id_list', 'registered_device_key_list', 'users', 'operations'];

    /**
     * A list as the JSON string Duo reads it as; a string is taken to be encoded already.
     *
     * @param  array<mixed>|string  $value
     */
    public static function jsonList(array|string $value): string
    {
        return is_string($value) ? $value : json_encode(array_values($value), JSON_THROW_ON_ERROR);
    }

    /**
     * Parameters as Duo reads them: a model or JsonSerializable as its array, the JSON_PARAMETERS lists encoded, a
     * date as a Unix timestamp (in seconds, or milliseconds when told), an enum as its value, and a null left out.
     *
     * @param  array<string, mixed>|Data|JsonSerializable  $parameters
     * @param  list<string>  $milliseconds  The parameters that take a time in milliseconds (the v2 logs' mintime and maxtime).
     * @return array<string, mixed>
     */
    public static function parameters(array|Data|JsonSerializable $parameters, array $milliseconds = []): array
    {
        if ($parameters instanceof Data) {
            $parameters = $parameters->toArray();
        } elseif ($parameters instanceof JsonSerializable) {
            $parameters = (array) $parameters->jsonSerialize();
        }

        $result = [];

        foreach ($parameters as $key => $value) {
            if ($value === null) {
                continue;
            }

            $result[$key] = match (true) {
                in_array($key, self::JSON_PARAMETERS, true) && (is_array($value) || is_string($value)) => self::jsonList($value),
                $value instanceof DateTimeInterface => self::timestamp($value, in_array($key, $milliseconds, true)),
                is_int($value) && in_array($key, $milliseconds, true) => self::timestamp($value, true),
                $value instanceof BackedEnum => $value->value,
                $value instanceof Data => $value->toArray(),
                default => $value,
            };
        }

        return $result;
    }

    /**
     * The smallest Unix timestamp that can only be in milliseconds (the year 5138 in seconds).
     */
    private const MILLISECONDS_FROM = 100_000_000_000;

    /**
     * A time as Duo takes one: a Unix timestamp in seconds, or in milliseconds (13 digits) for the v2 logs. An
     * integer is taken to be one already, except that seconds given for a millisecond parameter (a number too
     * small to be milliseconds, which Duo refuses) are scaled.
     */
    public static function timestamp(DateTimeInterface|int $time, bool $milliseconds = false): int
    {
        if (is_int($time)) {
            return $milliseconds && $time < self::MILLISECONDS_FROM ? $time * 1000 : $time;
        }

        return $milliseconds ? (int) $time->format('Uv') : (int) $time->format('U');
    }
}
