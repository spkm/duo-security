<?php

namespace spkm\DuoSecurity\Admin\Data\Integrations;

use spkm\DuoSecurity\Data\Data;

/**
 * An integration (an application protected by Duo), with its keys, policy and, for an Admin API application, its permissions; the adminapi_subaccount_* permissions land in $extra.
 */
readonly class Integration extends Data
{
    /**
     * @param  array<mixed>|null  $compliance_requirements
     * @param  array<mixed>|null  $business_owners
     * @param  array<mixed>|null  $technical_owners
     * @param  array<mixed>|null  $groups_allowed
     * @param  array<mixed>|null  $sso
     * @param  array<mixed>|null  $ip_whitelist
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $integration_key = null,
        public ?string $secret_key = null,
        public ?string $name = null,
        public ?string $type = null,
        public ?string $notes = null,
        public ?string $greeting = null,
        public ?string $policy_key = null,
        public ?string $sensitivity_level = null,
        public ?array $compliance_requirements = null,
        public ?array $business_owners = null,
        public ?array $technical_owners = null,
        public ?string $username_normalization_policy = null,
        public ?string $user_access = null,
        public ?array $groups_allowed = null,
        public ?string $networks_for_api_access = null,
        public ?bool $self_service_allowed = null,
        public ?bool $prompt_v4_enabled = null,
        public ?bool $frameless_auth_prompt_enabled = null,
        public ?bool $web_referers_enabled = null,
        public ?string $missing_web_referer_policy = null,
        public ?array $sso = null,
        public ?bool $adminapi_admins = null,
        public ?bool $adminapi_admins_read = null,
        public ?bool $adminapi_allow_to_set_permissions = null,
        public ?bool $adminapi_identity_verification_read = null,
        public ?bool $adminapi_identity_verification_write = null,
        public ?bool $adminapi_info = null,
        public ?bool $adminapi_integrations = null,
        public ?bool $adminapi_read_log = null,
        public ?bool $adminapi_read_resource = null,
        public ?bool $adminapi_settings = null,
        public ?bool $adminapi_write_resource = null,
        public ?string $enroll_policy = null,
        public ?array $ip_whitelist = null,
        public ?string $ip_whitelist_enroll_policy = null,
        public ?int $trusted_device_days = null,
        public ?bool $os_logon_pwl_enabled = null,
        public ?string $visual_style = null,
        public array $extra = [],
    ) {}
}
