<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * An authorization decision from the v2 authorization log.
 */
readonly class AuthorizationLogEvent extends Data
{
    /**
     * @param  array<mixed>|null  $application
     * @param  array<mixed>|null  $user
     * @param  array<mixed>|null  $non_human_identity
     * @param  array<mixed>|null  $effective_authorization_policy
     * @param  array<mixed>|null  $evaluation_result
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $akey = null,
        public ?string $txid = null,
        public ?CarbonImmutable $ts = null,
        public ?string $type = null,
        public ?string $authorization_result = null,
        public ?string $reason = null,
        public ?array $application = null,
        public ?array $user = null,
        public ?array $non_human_identity = null,
        public ?array $effective_authorization_policy = null,
        public ?array $evaluation_result = null,
        public array $extra = [],
    ) {}
}
