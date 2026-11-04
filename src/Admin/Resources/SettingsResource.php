<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Settings\Settings;
use spkm\DuoSecurity\Admin\Requests\Settings\DeleteLogoRequest;
use spkm\DuoSecurity\Admin\Requests\Settings\GetLogoRequest;
use spkm\DuoSecurity\Admin\Requests\Settings\GetSettingsRequest;
use spkm\DuoSecurity\Admin\Requests\Settings\ModifyLogoRequest;
use spkm\DuoSecurity\Admin\Requests\Settings\ModifySettingsRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Settings endpoints of the Admin API, from DuoSecurity::settings().
 */
class SettingsResource extends BaseResource
{
    /**
     * Retrieve the account's settings: GET /admin/v1/settings.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getSettings(array $params = []): Settings
    {
        return $this->fetch(new GetSettingsRequest($params));
    }

    /**
     * Modify the account's settings: POST /admin/v1/settings.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: caller_id, duo_mobile_otp_type, email_activity_notification_enabled, enrollment_universal_prompt_enabled, fraud_email, fraud_email_enabled, global_ssp_policy_enforced, helpdesk_bypass, helpdesk_bypass_expiration, helpdesk_can_send_enroll_email, helpdesk_message, inactive_user_expiration, instant_restore_enabled, keypress_confirm, keypress_fraud, language, lockout_expire_duration, lockout_threshold, log_retention_days, minimum_password_length, mobile_otp_enabled, muted_push_enabled, name, password_requires_lower_alpha, password_requires_numeric, password_requires_special, password_requires_upper_alpha, pending_deletion_days, push_activity_notification_enabled, push_enabled, security_checkup_enabled, sms_batch, sms_enabled, sms_expiration, sms_message, sms_refresh, telephony_warning_min, timezone, u2f_enabled, unenrolled_user_lockout_threshold, user_managers_can_put_users_in_bypass, user_telephony_cost_max, voice_enabled.
     */
    public function modifySettings(array $params = []): Settings
    {
        return $this->fetch(new ModifySettingsRequest($params));
    }

    /**
     * Retrieve the account's logo, as PNG bytes: GET /admin/v1/logo.
     */
    public function getLogo(): string
    {
        return $this->fetch(new GetLogoRequest);
    }

    /**
     * Set the account's logo (a PNG, base64-encoded): POST /admin/v1/logo.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function modifyLogo(string $logo, array $params = []): bool
    {
        return $this->perform(new ModifyLogoRequest($logo, $params));
    }

    /**
     * Delete the account's logo: DELETE /admin/v1/logo.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteLogo(array $params = []): bool
    {
        return $this->perform(new DeleteLogoRequest($params));
    }
}
