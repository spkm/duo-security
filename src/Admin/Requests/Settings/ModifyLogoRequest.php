<?php

namespace spkm\DuoSecurity\Admin\Requests\Settings;

use spkm\DuoSecurity\Requests\WriteRequest;

/**
 * Set the account's logo (a PNG, base64-encoded): POST /admin/v1/logo. Requires the "Grant settings" permission.
 */
class ModifyLogoRequest extends WriteRequest
{
    protected string $responseShape = self::NONE;

    /**
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function __construct(string $logo, array $params = [])
    {
        $this->params = ['logo' => $logo, ...$params];
    }

    public function resolveEndpoint(): string
    {
        return '/admin/v1/logo';
    }
}
