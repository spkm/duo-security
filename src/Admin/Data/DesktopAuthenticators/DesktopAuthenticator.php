<?php

namespace spkm\DuoSecurity\Admin\Data\DesktopAuthenticators;

use spkm\DuoSecurity\Admin\Data\Users\User;
use spkm\DuoSecurity\Data\Data;

/**
 * A Duo Desktop authenticator and the user it belongs to.
 */
readonly class DesktopAuthenticator extends Data
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $daid = null,
        public ?string $dakey = null,
        public ?string $device_name = null,
        public ?string $duo_desktop_version = null,
        public ?string $os_family = null,
        public ?User $user = null,
        public array $extra = [],
    ) {}
}
