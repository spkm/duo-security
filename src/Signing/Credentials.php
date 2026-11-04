<?php

namespace spkm\DuoSecurity\Signing;

use InvalidArgumentException;
use LogicException;

/**
 * One Duo application's keys: the integration key (the username of every request), the secret key (which signs
 * every request and is never sent) and the account's API hostname, such as api-xxxxxxxx.duosecurity.com. The Admin
 * API and the Auth API are separate applications in the Duo Admin Panel, each with its own pair of keys.
 */
final readonly class Credentials
{
    /**
     * The API hostname, lowercase and without a scheme or path, as it is written into every signature.
     */
    public string $apiHostname;

    public function __construct(
        public string $integrationKey,
        #[\SensitiveParameter] private string $secretKey,
        string $apiHostname,
    ) {
        if (trim($integrationKey) === '' || trim($secretKey) === '') {
            throw new InvalidArgumentException('Duo credentials need an integration key and a secret key.');
        }

        $host = strtolower(trim($apiHostname));
        $host = (string) preg_replace('#^[a-z][a-z0-9+.-]*://#', '', $host);
        $host = (string) strtok($host, '/');

        if ($host === '' || str_contains($host, ' ')) {
            throw new InvalidArgumentException('Duo credentials need the API hostname, such as api-xxxxxxxx.duosecurity.com.');
        }

        $this->apiHostname = $host;
    }

    /**
     * The secret key, for the signer only.
     */
    public function secretKey(): string
    {
        return $this->secretKey;
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
     * What signs every request with these credentials.
     */
    public function authenticator(): DuoAuthenticator
    {
        return new DuoAuthenticator($this);
    }

    /**
     * What a dump shows: everything but the secret key.
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
