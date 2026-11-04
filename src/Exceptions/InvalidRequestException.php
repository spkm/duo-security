<?php

namespace spkm\DuoSecurity\Exceptions;

use spkm\DuoSecurity\DuoApiException;

/**
 * Duo refused what was sent (400 and the other client errors): a parameter missing (40001) or invalid (40002), a
 * duplicate (40003), an operation that failed (40004), a wrong HTTP method (405).
 */
class InvalidRequestException extends DuoApiException {}
