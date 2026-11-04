<?php

namespace spkm\DuoSecurity\Admin\Requests\Administrators;

use spkm\DuoSecurity\Admin\Data\Administrators\AllowedAuthMethods;
use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Set the factors administrators may log in to the Admin Panel with: POST /admin/v1/admins/allowed_auth_methods. Requires the "Grant administrators - Write" permission.
 *
 * Parameters Duo documents: hardware_token_enabled, mobile_otp_enabled, push_enabled, sms_enabled, verified_push_enabled, verified_push_length, voice_enabled, webauthn_enabled, yubikey_enabled.
 */
class SetAllowedAdministratorAuthMethodsRequest extends WriteRequest
{
    protected ?string $dto = AllowedAuthMethods::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: hardware_token_enabled, mobile_otp_enabled, push_enabled, sms_enabled, verified_push_enabled, verified_push_length, voice_enabled, webauthn_enabled, yubikey_enabled.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/admins/allowed_auth_methods';
    }
}
