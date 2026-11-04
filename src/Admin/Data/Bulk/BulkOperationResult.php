<?php

namespace spkm\DuoSecurity\Admin\Data\Bulk;

use spkm\DuoSecurity\Data\Data;

/**
 * The answer to one operation of a bulk call: an OK envelope's response, or a FAIL's code and message.
 */
readonly class BulkOperationResult extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $stat = null,
        public ?int $code = null,
        public ?string $message = null,
        public ?string $message_detail = null,
        public mixed $response = null,
        public array $extra = [],
    ) {}
}
