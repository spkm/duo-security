<?php

namespace spkm\DuoSecurity\Admin\Resources\Concerns;

use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Admin\Requests\Phones\GetPhoneRequest;

/**
 * Finders for phones, beside the endpoints' own methods: by id (null when there is none) and by number, which Duo
 * matches exactly (in E.164 form, +441234567890), one phone at most.
 */
trait FindsPhones
{
    /**
     * The phone with this id, or null when Duo has none.
     */
    public function find(string $phoneId): ?Phone
    {
        return $this->fetchOrNull(new GetPhoneRequest($phoneId));
    }

    /**
     * The phone with this number (and extension, when given), or null when Duo has none.
     */
    public function findByNumber(string $number, ?string $extension = null): ?Phone
    {
        return $this->listPhones(array_filter(['number' => $number, 'extension' => $extension], fn (?string $value) => $value !== null))->first();
    }
}
