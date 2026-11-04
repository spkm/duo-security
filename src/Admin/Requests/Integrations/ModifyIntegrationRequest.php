<?php

namespace spkm\DuoSecurity\Admin\Requests\Integrations;

use spkm\DuoSecurity\Admin\Data\Integrations\Integration;
use spkm\DuoSecurity\Requests\WriteRequest;
use spkm\DuoSecurity\Support\Path;

/**
 * Modify an integration, its permissions or reset its secret key: POST /admin/v3/integrations/{integration_key}. Requires the "Grant applications" permission.
 *
 * Needs v5 signing, which this package uses.
 *
 * Parameters Duo documents: name, adminapi_admins, adminapi_admins_read, adminapi_allow_to_set_permissions, adminapi_identity_verification_read, adminapi_identity_verification_write, adminapi_info, adminapi_integrations, adminapi_read_log, adminapi_read_resource, adminapi_settings, adminapi_write_resource, adminapi_subaccount_*, greeting, groups_allowed, networks_for_api_access, notes, policy_key, prompt_v4_enabled, reset_secret_key, self_service_allowed, sso, user_access, username_normalization_policy.
 */
class ModifyIntegrationRequest extends WriteRequest
{
    protected ?string $dto = Integration::class;

    /**
     * @param  array<string, mixed>  $params  The parameters Duo documents: name, adminapi_admins, adminapi_admins_read, adminapi_allow_to_set_permissions, adminapi_identity_verification_read, adminapi_identity_verification_write, adminapi_info, adminapi_integrations, adminapi_read_log, adminapi_read_resource, adminapi_settings, adminapi_write_resource, adminapi_subaccount_*, greeting, groups_allowed, networks_for_api_access, notes, policy_key, prompt_v4_enabled, reset_secret_key, self_service_allowed, sso, user_access, username_normalization_policy.
     */
    public function __construct(protected string $integrationKey, array $params = [])
    {
        $this->params = $params;
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v3/integrations/'.Path::segment($this->integrationKey);
    }
}
