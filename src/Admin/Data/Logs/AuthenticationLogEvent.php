<?php

namespace spkm\DuoSecurity\Admin\Data\Logs;

use Carbon\CarbonImmutable;
use spkm\DuoSecurity\Data\Data;

/**
 * An authentication (or enrollment) event from the v2 authentication log, with the user, application and devices involved and Duo's assessments.
 */
readonly class AuthenticationLogEvent extends Data
{
    /**
     * @param  array<mixed>|null  $adaptive_trust_assessments
     * @param  array<mixed>|null  $passport_assessment
     * @param  array<mixed>|null  $trusted_session_info
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $txid = null,
        public ?CarbonImmutable $timestamp = null,
        public ?CarbonImmutable $isotimestamp = null,
        public ?string $event_type = null,
        public ?string $factor = null,
        public ?string $reason = null,
        public ?string $result = null,
        public ?string $ood_software = null,
        public ?string $trusted_endpoint_status = null,
        public ?string $alias = null,
        public ?string $email = null,
        public ?LogUser $user = null,
        public ?LogApplication $application = null,
        public ?AuthDevice $auth_device = null,
        public ?AccessDevice $access_device = null,
        public ?array $adaptive_trust_assessments = null,
        public ?array $passport_assessment = null,
        public ?array $trusted_session_info = null,
        public array $extra = [],
    ) {}
}
