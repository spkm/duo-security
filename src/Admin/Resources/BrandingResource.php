<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Branding\Branding;
use spkm\DuoSecurity\Admin\Data\Branding\CustomMessaging;
use spkm\DuoSecurity\Admin\Requests\Branding\AddDraftBrandingUserRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\GetBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\GetCustomMessagingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\GetDraftBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\ModifyBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\ModifyCustomMessagingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\ModifyDraftBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\PublishDraftBrandingRequest;
use spkm\DuoSecurity\Admin\Requests\Branding\RemoveDraftBrandingUserRequest;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Custom Branding endpoints of the Admin API, from DuoSecurity::branding().
 */
class BrandingResource extends BaseResource
{
    /**
     * Retrieve the live custom branding: GET /admin/v1/branding.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getBranding(array $params = []): Branding
    {
        return $this->fetch(new GetBrandingRequest($params));
    }

    /**
     * Modify the live custom branding: POST /admin/v1/branding.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: background_img, card_accent_color, logo, page_background_color, powered_by_duo, sso_custom_username_label.
     */
    public function modifyBranding(array $params = []): Branding
    {
        return $this->fetch(new ModifyBrandingRequest($params));
    }

    /**
     * Retrieve the draft custom branding: GET /admin/v1/branding/draft.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getDraftBranding(array $params = []): Branding
    {
        return $this->fetch(new GetDraftBrandingRequest($params));
    }

    /**
     * Modify the draft custom branding: POST /admin/v1/branding/draft.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: background_img, card_accent_color, logo, page_background_color, powered_by_duo, sso_custom_username_label, user_ids.
     */
    public function modifyDraftBranding(array $params = []): Branding
    {
        return $this->fetch(new ModifyDraftBrandingRequest($params));
    }

    /**
     * Show the draft custom branding to a user: POST /admin/v1/branding/draft/users/{user_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function addDraftBrandingUser(string $userId, array $params = []): bool
    {
        return $this->perform(new AddDraftBrandingUserRequest($userId, $params));
    }

    /**
     * Stop showing the draft custom branding to a user: DELETE /admin/v1/branding/draft/users/{user_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function removeDraftBrandingUser(string $userId, array $params = []): bool
    {
        return $this->perform(new RemoveDraftBrandingUserRequest($userId, $params));
    }

    /**
     * Publish the draft custom branding as the live one: POST /admin/v1/branding/draft/publish.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function publishDraftBranding(array $params = []): bool
    {
        return $this->perform(new PublishDraftBrandingRequest($params));
    }

    /**
     * Retrieve the custom messaging shown to users: GET /admin/v1/branding/custom_messaging.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getCustomMessaging(array $params = []): CustomMessaging
    {
        return $this->fetch(new GetCustomMessagingRequest($params));
    }

    /**
     * Modify the custom messaging shown to users: POST /admin/v1/branding/custom_messaging.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: help_links, help_text, locale.
     */
    public function modifyCustomMessaging(array $params = []): CustomMessaging
    {
        return $this->fetch(new ModifyCustomMessagingRequest($params));
    }
}
