<?php

namespace spkm\DuoSecurity\Admin\Data\Settings;

use spkm\DuoSecurity\Data\Data;

/**
 * The account's settings, as the Admin Panel's Settings page shows them.
 */
readonly class Settings extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $name = null,
        public ?string $caller_id = null,
        public ?string $duo_mobile_otp_type = null,
        public ?bool $email_activity_notification_enabled = null,
        public ?bool $enrollment_universal_prompt_enabled = null,
        public ?string $fraud_email = null,
        public ?bool $fraud_email_enabled = null,
        public ?bool $global_ssp_policy_enforced = null,
        public ?string $helpdesk_bypass = null,
        public ?int $helpdesk_bypass_expiration = null,
        public ?bool $helpdesk_can_send_enroll_email = null,
        public ?bool $helpdesk_clear_step_up = null,
        public ?string $helpdesk_message = null,
        public ?int $inactive_admin_expiration = null,
        public ?int $inactive_user_expiration = null,
        public ?bool $instant_restore_enabled = null,
        public ?string $keypress_confirm = null,
        public ?string $keypress_fraud = null,
        public ?string $language = null,
        public ?int $lockout_expire_duration = null,
        public ?int $lockout_threshold = null,
        public ?int $log_retention_days = null,
        public ?int $minimum_password_length = null,
        public ?bool $mobile_analytics_disabled = null,
        public ?bool $mobile_otp_enabled = null,
        public ?bool $muted_push_enabled = null,
        public ?bool $password_requires_lower_alpha = null,
        public ?bool $password_requires_numeric = null,
        public ?bool $password_requires_special = null,
        public ?bool $password_requires_upper_alpha = null,
        public ?int $pending_deletion_days = null,
        public ?bool $push_activity_notification_enabled = null,
        public ?bool $push_enabled = null,
        public ?bool $security_checkup_enabled = null,
        public ?int $sms_batch = null,
        public ?bool $sms_enabled = null,
        public ?int $sms_expiration = null,
        public ?string $sms_message = null,
        public ?int $sms_refresh = null,
        public ?int $telephony_warning_min = null,
        public ?string $timezone = null,
        public ?int $unenrolled_user_lockout_threshold = null,
        public ?bool $user_managers_can_put_users_in_bypass = null,
        public ?int $user_telephony_cost_max = null,
        public ?bool $voice_enabled = null,
        public array $extra = [],
    ) {}
}
