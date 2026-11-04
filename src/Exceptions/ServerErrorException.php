<?php

namespace spkm\DuoSecurity\Exceptions;

use spkm\DuoSecurity\DuoApiException;

/**
 * Duo failed on its side (5xx, code 50001), the request timed out (408), or Duo answered a 2xx with something
 * other than an OK envelope.
 */
class ServerErrorException extends DuoApiException {}
