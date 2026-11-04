<?php

namespace spkm\DuoSecurity\Signing;

use LogicException;
use WeakMap;

/**
 * Where a Credentials object's secret key lives: beside the object rather than in it, so that no dump of the
 * credentials, of a connector or of an exception that reaches them (VarDumper's dump() and dd(), var_export, a
 * cast to array) can show it. The entry goes when the credentials do.
 *
 * @internal
 */
final class KeyHolder
{
    /** @var WeakMap<object, string>|null */
    private static ?WeakMap $keys = null;

    public static function put(object $owner, #[\SensitiveParameter] string $key): void
    {
        self::$keys ??= new WeakMap;
        self::$keys[$owner] = $key;
    }

    public static function get(object $owner): string
    {
        return self::$keys?->offsetExists($owner) ? self::$keys[$owner] : throw new LogicException('These credentials have no secret key: they were cloned or built without one.');
    }
}
