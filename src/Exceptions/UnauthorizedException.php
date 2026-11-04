<?php

namespace spkm\DuoSecurity\Exceptions;

use spkm\DuoSecurity\DuoApiException;

/**
 * Duo refused the keys, the signature or the date (401: codes 40101 to 40106), or the application lacks the
 * permission for the call or is of the wrong type, such as an Auth API key used on the Admin API (403, code 40301).
 */
class UnauthorizedException extends DuoApiException {}
