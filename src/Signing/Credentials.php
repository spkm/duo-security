<?php

namespace spkm\DuoSecurity\Signing;

use InvalidArgumentException;
use LogicException;

/**
 * One Duo application's keys: the integration key (the username of every request), the secret key (which signs
 * every request and is never sent, nor held in this object: see KeyHolder) and the account's API hostname, such
 * as api-xxxxxxxx.duosecurity.com. The Admin API and the Auth API are separate applications in the Duo Admin
 * Panel, each with its own pair of keys.
 */
final readonly class Credentials
{
    /**
     * A hostname: labels of letters, digits and hyphens joined by dots, at least two of them, as Duo's are
     * (api-xxxxxxxx.duosecurity.com). Anything else (a port, a path, a user, an IP address) is refused, so a
     * hostname taken from config can't send the keys and the request anywhere but the host that is signed for.
     */
    private const HOSTNAME = '/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z][a-z0-9-]{0,61}[a-z0-9]$/';

    /**
     * The API hostname, lowercase and without a scheme or path, as it is written into every signature.
     */
    public string $apiHostname;

    public function __construct(
        public string $integrationKey,
        #[\SensitiveParameter] string $secretKey,
        string $apiHostname,
    ) {
        if (trim($integrationKey) === '' || trim($secretKey) === '') {
            throw new InvalidArgumentException('Duo credentials need an integration key and a secret key.');
        }

        $host = strtolower(trim($apiHostname));
        $host = (string) preg_replace('#^[a-z][a-z0-9+.-]*://#', '', $host);
        $host = (string) strtok($host, '/');

        if (preg_match(self::HOSTNAME, $host) !== 1) {
            throw new InvalidArgumentException('Duo credentials need the API hostname as the Admin Panel shows it, such as api-xxxxxxxx.duosecurity.com.');
        }

        $this->apiHostname = $host;
        KeyHolder::put($this, $secretKey);
    }

    /**
     * Who signs in: the integration key, which Duo shows in the Admin Panel and which identifies the application
     * without being a secret.
     */
    public function identity(): string
    {
        return $this->integrationKey;
    }

    /**
     * The signature of a canonical string (DuoSigner::canonical()): its HMAC-SHA512 with the secret key, as
     * lowercase hex.
     */
    public function signature(string $canonical): string
    {
        return DuoSigner::signature(KeyHolder::get($this), $canonical);
    }

    /**
     * A fingerprint of the account, the application and its secret, so that what one application fetched is never
     * served to another (the response cache keys on it), without any of the keys being written anywhere: an HMAC
     * of the hostname and the integration key with the secret key.
     */
    public function fingerprint(): string
    {
        return hash_hmac('sha256', $this->apiHostname.'|'.$this->integrationKey, KeyHolder::get($this));
    }

    /**
     * What signs every request with these credentials.
     */
    public function authenticator(): DuoAuthenticator
    {
        return new DuoAuthenticator($this);
    }

    /**
     * What a dump shows: everything but the secret key, which is not in the object anyway.
     *
     * @return array<string, mixed>
     */
    public function __debugInfo(): array
    {
        return [
            'integrationKey' => $this->integrationKey,
            'secretKey' => '[redacted]',
            'apiHostname' => $this->apiHostname,
        ];
    }

    /**
     * Credentials are never written out with a queued job or a session; build them from config where they are
     * needed.
     *
     * @return array<string, mixed>
     */
    public function __serialize(): array
    {
        throw new LogicException('Duo credentials cannot be serialised; resolve the connector from the DuoSecurity facade or config where it is used.');
    }
}
