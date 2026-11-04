<?php

namespace spkm\DuoSecurity\Exceptions;

use spkm\DuoSecurity\DuoApiException;

/**
 * Duo has no record at the path (404, code 40401).
 */
class NotFoundException extends DuoApiException {}
