<?php

namespace spkm\DuoSecurity\Admin\Resources;

use Illuminate\Support\Collection;
use spkm\DuoSecurity\Admin\Data\BypassCodes\BypassCode;
use spkm\DuoSecurity\Admin\Data\DesktopAuthenticators\DesktopAuthenticator;
use spkm\DuoSecurity\Admin\Data\Groups\Group;
use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Admin\Data\Tokens\Token;
use spkm\DuoSecurity\Admin\Data\Users\DirectorySync;
use spkm\DuoSecurity\Admin\Data\Users\DirectorySyncResult;
use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Admin\Data\Users\VerificationPush;
use spkm\DuoSecurity\Admin\Data\Users\VerificationPushResponse;
use spkm\DuoSecurity\Admin\Data\WebAuthn\WebAuthnCredential;
use spkm\DuoSecurity\Admin\Requests\Users\AddUserPhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Users\AddUserToGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Users\AddUserTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Users\BulkCreateUsersRequest;
use spkm\DuoSecurity\Admin\Requests\Users\BulkRestoreUsersRequest;
use spkm\DuoSecurity\Admin\Requests\Users\BulkSendUsersToTrashRequest;
use spkm\DuoSecurity\Admin\Requests\Users\CreateUserBypassCodesRequest;
use spkm\DuoSecurity\Admin\Requests\Users\CreateUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\DeleteUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\EnrollUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\GetUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\GetUserVerificationPushResponseRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListDirectorySyncsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserBypassCodesRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserDesktopAuthenticatorsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserGroupsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserPhonesRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUsersRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserTokensRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserU2fTokensRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ListUserWebAuthnCredentialsRequest;
use spkm\DuoSecurity\Admin\Requests\Users\ModifyUserRequest;
use spkm\DuoSecurity\Admin\Requests\Users\RemoveUserFromGroupRequest;
use spkm\DuoSecurity\Admin\Requests\Users\RemoveUserPhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Users\RemoveUserTokenRequest;
use spkm\DuoSecurity\Admin\Requests\Users\SendUserVerificationPushRequest;
use spkm\DuoSecurity\Admin\Requests\Users\SyncUserFromDirectoryRequest;
use spkm\DuoSecurity\Admin\Resources\Concerns\FindsUsers;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Users endpoints of the Admin API, from DuoSecurity::users(). Beside the endpoints' own methods, find() gives a record by id, or null.
 */
class UsersResource extends BaseResource
{
    use FindsUsers;

    /**
     * Retrieve users, or the one with a username or email: GET /admin/v1/users.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset, username, email, user_id_list, username_list.
     * @return ListQuery<User>
     */
    public function listUsers(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListUsersRequest([...$params, ...$page]), User::class);
    }

    /**
     * Create a user: POST /admin/v1/users.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: username, alias1, alias2, alias3, alias4, aliases, custom_attributes, date_of_birth, email, enable_auto_prompt, firstname, lastname, notes, realname, status.
     */
    public function createUser(array $params = []): User
    {
        return $this->fetch(new CreateUserRequest($params));
    }

    /**
     * Create up to 100 users in one call (at most 50 calls a minute): POST /admin/v1/users/bulk_create.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: users.
     * @return Collection<int, User>
     */
    public function bulkCreateUsers(array $params = []): Collection
    {
        return $this->fetch(new BulkCreateUsersRequest($params));
    }

    /**
     * Restore up to 100 users from the Trash: POST /admin/v1/users/bulk_restore.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: user_id_list.
     */
    public function bulkRestoreUsers(array $params = []): string
    {
        return (string) $this->fetch(new BulkRestoreUsersRequest($params));
    }

    /**
     * Send up to 100 users to the Trash, for permanent deletion after seven days: POST /admin/v1/users/bulk_send_to_trash.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: user_id_list.
     */
    public function bulkSendUsersToTrash(array $params = []): mixed
    {
        return $this->fetch(new BulkSendUsersToTrashRequest($params));
    }

    /**
     * Retrieve a user: GET /admin/v1/users/{user_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getUser(string $userId, array $params = []): User
    {
        return $this->fetch(new GetUserRequest($userId, $params));
    }

    /**
     * Modify a user: POST /admin/v1/users/{user_id}.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: username, alias1, alias2, alias3, alias4, aliases, custom_attributes, date_of_birth, email, enable_auto_prompt, firstname, lastname, notes, realname, status.
     */
    public function modifyUser(string $userId, array $params = []): User
    {
        return $this->fetch(new ModifyUserRequest($userId, $params));
    }

    /**
     * Delete a user permanently, without the Trash (their phones stay): DELETE /admin/v1/users/{user_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deleteUser(string $userId, array $params = []): bool
    {
        return $this->perform(new DeleteUserRequest($userId, $params));
    }

    /**
     * Create a user and email them an enrollment link; the enrollment code is returned: POST /admin/v1/users/enroll.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: valid_secs.
     */
    public function enrollUser(string $username, string $email, array $params = []): string
    {
        return (string) $this->fetch(new EnrollUserRequest($username, $email, $params));
    }

    /**
     * Retrieve a user's bypass codes (their metadata, not the codes): GET /admin/v1/users/{user_id}/bypass_codes.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<BypassCode>
     */
    public function listUserBypassCodes(string $userId, array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListUserBypassCodesRequest($userId, [...$params, ...$page]), BypassCode::class);
    }

    /**
     * Create bypass codes for a user, replacing their existing ones unless preserve_existing is set; the codes are returned: POST /admin/v1/users/{user_id}/bypass_codes.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: count, codes, preserve_existing, reuse_count, valid_secs.
     * @return Collection<int, array<string, mixed>|string>
     */
    public function createUserBypassCodes(string $userId, array $params = []): Collection
    {
        return $this->fetch(new CreateUserBypassCodesRequest($userId, $params));
    }

    /**
     * Retrieve a user's groups: GET /admin/v1/users/{user_id}/groups.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<Group>
     */
    public function listUserGroups(string $userId, array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListUserGroupsRequest($userId, [...$params, ...$page]), Group::class);
    }

    /**
     * Add a user to a group: POST /admin/v1/users/{user_id}/groups.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function addUserToGroup(string $userId, string $groupId, array $params = []): bool
    {
        return $this->perform(new AddUserToGroupRequest($userId, $groupId, $params));
    }

    /**
     * Remove a user from a group: DELETE /admin/v1/users/{user_id}/groups/{group_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function removeUserFromGroup(string $userId, string $groupId, array $params = []): bool
    {
        return $this->perform(new RemoveUserFromGroupRequest($userId, $groupId, $params));
    }

    /**
     * Retrieve a user's phones: GET /admin/v1/users/{user_id}/phones.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<Phone>
     */
    public function listUserPhones(string $userId, array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListUserPhonesRequest($userId, [...$params, ...$page]), Phone::class);
    }

    /**
     * Associate a phone with a user: POST /admin/v1/users/{user_id}/phones.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function addUserPhone(string $userId, string $phoneId, array $params = []): bool
    {
        return $this->perform(new AddUserPhoneRequest($userId, $phoneId, $params));
    }

    /**
     * Disassociate a phone from a user (the phone itself stays): DELETE /admin/v1/users/{user_id}/phones/{phone_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function removeUserPhone(string $userId, string $phoneId, array $params = []): bool
    {
        return $this->perform(new RemoveUserPhoneRequest($userId, $phoneId, $params));
    }

    /**
     * Retrieve a user's hardware tokens: GET /admin/v1/users/{user_id}/tokens.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<Token>
     */
    public function listUserTokens(string $userId, array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListUserTokensRequest($userId, [...$params, ...$page]), Token::class);
    }

    /**
     * Associate a hardware token with a user: POST /admin/v1/users/{user_id}/tokens.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function addUserToken(string $userId, string $tokenId, array $params = []): bool
    {
        return $this->perform(new AddUserTokenRequest($userId, $tokenId, $params));
    }

    /**
     * Disassociate a hardware token from a user: DELETE /admin/v1/users/{user_id}/tokens/{token_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function removeUserToken(string $userId, string $tokenId, array $params = []): bool
    {
        return $this->perform(new RemoveUserTokenRequest($userId, $tokenId, $params));
    }

    /**
     * Retrieve a user's U2F tokens (Duo now answers 403): GET /admin/v1/users/{user_id}/u2ftokens.
     *
     * @deprecated Duo deprecated this endpoint.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     * @return Collection<int, array<string, mixed>|string>
     */
    public function listUserU2fTokens(string $userId, array $params = []): Collection
    {
        return $this->fetch(new ListUserU2fTokensRequest($userId, $params));
    }

    /**
     * Retrieve a user's WebAuthn credentials: GET /admin/v1/users/{user_id}/webauthncredentials.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     * @return Collection<int, WebAuthnCredential>
     */
    public function listUserWebAuthnCredentials(string $userId, array $params = []): Collection
    {
        return $this->fetch(new ListUserWebAuthnCredentialsRequest($userId, $params));
    }

    /**
     * Retrieve a user's Duo Desktop authenticators: GET /admin/v1/users/{user_id}/desktopauthenticators.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset.
     * @return ListQuery<DesktopAuthenticator>
     */
    public function listUserDesktopAuthenticators(string $userId, array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListUserDesktopAuthenticatorsRequest($userId, [...$params, ...$page]), DesktopAuthenticator::class);
    }

    /**
     * Retrieve the user directory syncs: GET /admin/v1/users/directorysync.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     * @return Collection<int, DirectorySync>
     */
    public function listDirectorySyncs(array $params = []): Collection
    {
        return $this->fetch(new ListDirectorySyncsRequest($params));
    }

    /**
     * Synchronize one user from a directory: POST /admin/v1/users/directorysync/{directory_key}/syncuser.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function syncUserFromDirectory(string $directoryKey, string $username, array $params = []): DirectorySyncResult
    {
        return $this->fetch(new SyncUserFromDirectoryRequest($directoryKey, $username, $params));
    }

    /**
     * Send a verification Duo Push to one of a user's phones: POST /admin/v1/users/{user_id}/send_verification_push.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function sendUserVerificationPush(string $userId, string $phoneId, array $params = []): VerificationPush
    {
        return $this->fetch(new SendUserVerificationPushRequest($userId, $phoneId, $params));
    }

    /**
     * Retrieve the answer to a verification push (gone 120 seconds after it was sent): GET /admin/v1/users/{user_id}/verification_push_response.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getUserVerificationPushResponse(string $userId, string $pushId, array $params = []): VerificationPushResponse
    {
        return $this->fetch(new GetUserVerificationPushResponseRequest($userId, $pushId, $params));
    }
}
