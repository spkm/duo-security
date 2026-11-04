<?php

namespace spkm\DuoSecurity\Admin\Requests\Settings;

use spkm\DuoSecurity\Admin\Data\Settings\Settings;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Modify the account's settings: POST /admin/v1/settings. Requires the "Grant settings" permission.
 *
 * Parameters Duo documents: caller_id, duo_mobile_otp_type, email_activity_notification_enabled, enrollment_universal_prompt_enabled, fraud_email, fraud_email_enabled, global_ssp_policy_enforced, helpdesk_bypass, helpdesk_bypass_expiration, helpdesk_can_send_enroll_email, helpdesk_message, inactive_user_expiration, instant_restore_enabled, keypress_confirm, keypress_fraud, language, lockout_expire_duration, lockout_threshold, log_retention_days, minimum_password_length, mobile_otp_enabled, muted_push_enabled, name, password_requires_lower_alpha, password_requires_numeric, password_requires_special, password_requires_upper_alpha, pending_deletion_days, push_activity_notification_enabled, push_enabled, security_checkup_enabled, sms_batch, sms_enabled, sms_expiration, sms_message, sms_refresh, telephony_warning_min, timezone, u2f_enabled, unenrolled_user_lockout_threshold, user_managers_can_put_users_in_bypass, user_telephony_cost_max, voice_enabled.
 */
class ModifySettingsRequest extends WriteRequest
{
    protected ?string $dto = Settings::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: caller_id, duo_mobile_otp_type, email_activity_notification_enabled, enrollment_universal_prompt_enabled, fraud_email, fraud_email_enabled, global_ssp_policy_enforced, helpdesk_bypass, helpdesk_bypass_expiration, helpdesk_can_send_enroll_email, helpdesk_message, inactive_user_expiration, instant_restore_enabled, keypress_confirm, keypress_fraud, language, lockout_expire_duration, lockout_threshold, log_retention_days, minimum_password_length, mobile_otp_enabled, muted_push_enabled, name, password_requires_lower_alpha, password_requires_numeric, password_requires_special, password_requires_upper_alpha, pending_deletion_days, push_activity_notification_enabled, push_enabled, security_checkup_enabled, sms_batch, sms_enabled, sms_expiration, sms_message, sms_refresh, telephony_warning_min, timezone, u2f_enabled, unenrolled_user_lockout_threshold, user_managers_can_put_users_in_bypass, user_telephony_cost_max, voice_enabled.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/settings';
    }
}
