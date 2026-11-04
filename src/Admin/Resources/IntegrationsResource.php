<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Integrations\ClientSecret;
use spkm\DuoSecurity\Admin\Data\Integrations\Integration;
use spkm\DuoSecurity\Admin\Data\Integrations\SecretKey;
use spkm\DuoSecurity\Admin\Requests\Integrations\CreateIntegrationRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\CreateIntegrationV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\DeleteIntegrationRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\DeleteIntegrationV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetIntegrationRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetIntegrationSecretKeyRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetIntegrationV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetOAuthClientSecretRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetOAuthClientSecretV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetOidcClientSecretRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\GetOidcClientSecretV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\ListIntegrationsRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\ListIntegrationsV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\ModifyIntegrationRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\ModifyIntegrationV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\ResetOAuthClientSecretRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\ResetOAuthClientSecretV2Request;
use spkm\DuoSecurity\Admin\Requests\Integrations\ResetOidcClientSecretRequest;
use spkm\DuoSecurity\Admin\Requests\Integrations\ResetOidcClientSecretV2Request;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Integrations endpoints of the Admin API, from DuoSecurity::integrations().
 */
class IntegrationsResource extends BaseResource
{
    /**
     * Retrieve integrations (applications): GET /admin/v3/integrations.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<Integration>
     */
    public function listIntegrations(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListIntegrationsRequest([...$params, ...$page]), Integration::class);
    }

    /**
     * Create an integration; its keys are generated: POST /admin/v3/integrations.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: adminapi_admins, adminapi_admins_read, adminapi_allow_to_set_permissions, adminapi_identity_verification_read, adminapi_identity_verification_write, adminapi_info, adminapi_integrations, adminapi_read_log, adminapi_read_resource, adminapi_settings, adminapi_write_resource, adminapi_subaccount_*, greeting, groups_allowed, networks_for_api_access, notes, policy_key, prompt_v4_enabled, reset_secret_key, self_service_allowed, sso, user_access, username_normalization_policy.
     */
    public function createIntegration(string $name, string $type, array $params = []): Integration
    {
        return $this->fetch(new CreateIntegrationRequest($name, $type, $params));
    }

    /**
     * Retrieve an integration: GET /admin/v3/integrations/{integration_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getIntegration(string $integrationKey, array $params = []): Integration
    {
        return $this->fetch(new GetIntegrationRequest($integrationKey, $params));
    }

    /**
     * Modify an integration, its permissions or reset its secret key: POST /admin/v3/integrations/{integration_key}.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: name, adminapi_admins, adminapi_admins_read, adminapi_allow_to_set_permissions, adminapi_identity_verification_read, adminapi_identity_verification_write, adminapi_info, adminapi_integrations, adminapi_read_log, adminapi_read_resource, adminapi_settings, adminapi_write_resource, adminapi_subaccount_*, greeting, groups_allowed, networks_for_api_access, notes, policy_key, prompt_v4_enabled, reset_secret_key, self_service_allowed, sso, user_access, username_normalization_policy.
     */
    public function modifyIntegration(string $integrationKey, array $params = []): Integration
    {
        return $this->fetch(new ModifyIntegrationRequest($integrationKey, $params));
    }

    /**
     * Delete an integration (remove Duo from the application first, or its logins fail): DELETE /admin/v3/integrations/{integration_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteIntegration(string $integrationKey, array $params = []): bool
    {
        return $this->perform(new DeleteIntegrationRequest($integrationKey, $params));
    }

    /**
     * Retrieve an integration's secret key in full: GET /admin/v1/integrations/{integration_key}/skey.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getIntegrationSecretKey(string $integrationKey, array $params = []): SecretKey
    {
        return $this->fetch(new GetIntegrationSecretKeyRequest($integrationKey, $params));
    }

    /**
     * Retrieve an OAuth client credentials application's client secret: GET /admin/v3/integrations/oauth_cc/{integration_key}/client_secret/{client_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getOAuthClientSecret(string $integrationKey, string $clientId, array $params = []): ClientSecret
    {
        return $this->fetch(new GetOAuthClientSecretRequest($integrationKey, $clientId, $params));
    }

    /**
     * Reset an OAuth client credentials application's client secret: POST /admin/v3/integrations/oauth_cc/{integration_key}/client_secret/{client_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function resetOAuthClientSecret(string $integrationKey, string $clientId, array $params = []): bool
    {
        return $this->perform(new ResetOAuthClientSecretRequest($integrationKey, $clientId, $params));
    }

    /**
     * Retrieve an OIDC application's client secret: GET /admin/v3/integrations/oidc/{integration_key}/client_secret.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getOidcClientSecret(string $integrationKey, array $params = []): ClientSecret
    {
        return $this->fetch(new GetOidcClientSecretRequest($integrationKey, $params));
    }

    /**
     * Reset an OIDC application's client secret: POST /admin/v3/integrations/oidc/{integration_key}/client_secret.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function resetOidcClientSecret(string $integrationKey, array $params = []): bool
    {
        return $this->perform(new ResetOidcClientSecretRequest($integrationKey, $params));
    }

    /**
     * Retrieve integrations (v2; Duo recommends v3): GET /admin/v2/integrations.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<Integration>
     */
    public function listIntegrationsV2(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListIntegrationsV2Request([...$params, ...$page]), Integration::class);
    }

    /**
     * Create an integration (v2; Duo recommends v3): POST /admin/v2/integrations.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: adminapi_admins, adminapi_admins_read, adminapi_allow_to_set_permissions, adminapi_identity_verification_read, adminapi_identity_verification_write, adminapi_info, adminapi_integrations, adminapi_read_log, adminapi_read_resource, adminapi_settings, adminapi_write_resource, adminapi_subaccount_*, greeting, groups_allowed, networks_for_api_access, notes, policy_key, prompt_v4_enabled, reset_secret_key, self_service_allowed, sso, username_normalization_policy.
     */
    public function createIntegrationV2(string $name, string $type, array $params = []): Integration
    {
        return $this->fetch(new CreateIntegrationV2Request($name, $type, $params));
    }

    /**
     * Retrieve an integration (v2; Duo recommends v3): GET /admin/v2/integrations/{integration_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getIntegrationV2(string $integrationKey, array $params = []): Integration
    {
        return $this->fetch(new GetIntegrationV2Request($integrationKey, $params));
    }

    /**
     * Modify an integration (v2; Duo recommends v3): POST /admin/v2/integrations/{integration_key}.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: name, adminapi_admins, adminapi_admins_read, adminapi_allow_to_set_permissions, adminapi_identity_verification_read, adminapi_identity_verification_write, adminapi_info, adminapi_integrations, adminapi_read_log, adminapi_read_resource, adminapi_settings, adminapi_write_resource, adminapi_subaccount_*, greeting, groups_allowed, networks_for_api_access, notes, policy_key, prompt_v4_enabled, reset_secret_key, self_service_allowed, sso, username_normalization_policy.
     */
    public function modifyIntegrationV2(string $integrationKey, array $params = []): Integration
    {
        return $this->fetch(new ModifyIntegrationV2Request($integrationKey, $params));
    }

    /**
     * Delete an integration (v2; Duo recommends v3): DELETE /admin/v2/integrations/{integration_key}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteIntegrationV2(string $integrationKey, array $params = []): bool
    {
        return $this->perform(new DeleteIntegrationV2Request($integrationKey, $params));
    }

    /**
     * Retrieve an OAuth client credentials application's client secret (v2): GET /admin/v2/integrations/oauth_cc/{integration_key}/client_secret/{client_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getOAuthClientSecretV2(string $integrationKey, string $clientId, array $params = []): ClientSecret
    {
        return $this->fetch(new GetOAuthClientSecretV2Request($integrationKey, $clientId, $params));
    }

    /**
     * Reset an OAuth client credentials application's client secret (v2): POST /admin/v2/integrations/oauth_cc/{integration_key}/client_secret/{client_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function resetOAuthClientSecretV2(string $integrationKey, string $clientId, array $params = []): bool
    {
        return $this->perform(new ResetOAuthClientSecretV2Request($integrationKey, $clientId, $params));
    }

    /**
     * Retrieve an OIDC application's client secret (v2): GET /admin/v2/integrations/oidc/{integration_key}/client_secret.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getOidcClientSecretV2(string $integrationKey, array $params = []): ClientSecret
    {
        return $this->fetch(new GetOidcClientSecretV2Request($integrationKey, $params));
    }

    /**
     * Reset an OIDC application's client secret (v2): POST /admin/v2/integrations/oidc/{integration_key}/client_secret.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function resetOidcClientSecretV2(string $integrationKey, array $params = []): bool
    {
        return $this->perform(new ResetOidcClientSecretV2Request($integrationKey, $params));
    }
}
