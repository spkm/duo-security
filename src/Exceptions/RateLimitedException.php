<?php

namespace spkm\DuoSecurity\Exceptions;

use spkm\DuoSecurity\DuoApiException;

/**
 * Duo refused the call because too many of its kind have been made recently (429, code 42901). Duo documents no
 * Retry-After header, but one is read when sent.
 */
class RateLimitedException extends DuoApiException
{
    /**
     * How many seconds to wait before calling again, from Retry-After; null when Duo didn't say.
     */
    public function retryAfter(): ?int
    {
        $retryAfter = $this->getResponse()->header('Retry-After');

        return is_string($retryAfter) && ctype_digit(trim($retryAfter)) ? (int) trim($retryAfter) : null;
    }
}
