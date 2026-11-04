<?php

use DuoAPI\Client;
use DuoAPI\Requester;
use spkm\DuoSecurity\Signing\Credentials;
use spkm\DuoSecurity\Signing\DuoSigner;

/**
 * Duo's own duo_api_php client (a development dependency only), with its network replaced by a requester that keeps
 * what the client would have sent: the URL, the method, the headers (Date, Authorization) and the body.
 */
final class RecordingRequester implements Requester
{
    /** @var list<array{url: string, method: string, headers: array<string, string>, body: ?string}> */
    public array $calls = [];

    public function options($options): void {}

    public function execute($url, $method, $headers, $body = null): array
    {
        $this->calls[] = ['url' => $url, 'method' => $method, 'headers' => $headers, 'body' => $body];

        return ['response' => '{"stat": "OK", "response": ""}', 'success' => true, 'http_status_code' => 200];
    }
}

/**
 * The Authorization header our signer computes for a request exactly as duo_api_php sent it.
 *
 * @param  array{url: string, method: string, headers: array<string, string>, body: ?string}  $call
 * @param  array<string, string>  $xDuoHeaders
 */
function ourAuthorizationFor(Credentials $credentials, array $call, array $xDuoHeaders = []): string
{
    $url = parse_url($call['url']);

    return DuoSigner::sign($credentials, $call['headers']['Date'], $call['method'], $url['path'] ?? '/', $url['query'] ?? '', $call['body'] ?? '', $xDuoHeaders);
}

$credentials = fn () => new Credentials('DIWJ8X6AEYOR5OMC6TQ1', 'Zh5eGmUq9zpfQnyUIu5OL9iWoMMv5ZNmk3zLJ4Ep', 'api-xxxxxxxx.duosecurity.com');

it('signs a GET with parameters as duo_api_php signs it', function () use ($credentials) {
    $requester = new RecordingRequester;
    $client = new Client('DIWJ8X6AEYOR5OMC6TQ1', 'Zh5eGmUq9zpfQnyUIu5OL9iWoMMv5ZNmk3zLJ4Ep', 'api-xxxxxxxx.duosecurity.com', $requester);
    $parameters = ['username' => 'root', 'realname' => 'First Last', 'email' => 'a+b@example.com', 'notes' => "caf\u{e9} ~ /"];

    $client->apiCall('GET', '/admin/v1/users', $parameters);
    $call = $requester->calls[0];

    expect($call['url'])->toBe('https://api-xxxxxxxx.duosecurity.com/admin/v1/users?'.DuoSigner::canonicalQuery($parameters))
        ->and(ourAuthorizationFor($credentials(), $call))->toBe($call['headers']['Authorization']);
});

it('signs a POST with a JSON body as duo_api_php signs it', function () use ($credentials) {
    $requester = new RecordingRequester;
    $client = new Client('DIWJ8X6AEYOR5OMC6TQ1', 'Zh5eGmUq9zpfQnyUIu5OL9iWoMMv5ZNmk3zLJ4Ep', 'api-xxxxxxxx.duosecurity.com', $requester);

    $client->apiCall('POST', '/admin/v1/users', ['username' => 'root', 'realname' => "First L\u{e9}st", 'notes' => 'a/b']);
    $call = $requester->calls[0];

    expect($call['headers']['Content-Type'])->toBe('application/json')
        ->and($call['body'])->toBe(json_encode(['notes' => 'a/b', 'realname' => "First L\u{e9}st", 'username' => 'root']))
        ->and(ourAuthorizationFor($credentials(), $call))->toBe($call['headers']['Authorization']);
});

it('signs a POST without parameters, whose body is {}, as duo_api_php signs it', function () use ($credentials) {
    $requester = new RecordingRequester;
    $client = new Client('DIWJ8X6AEYOR5OMC6TQ1', 'Zh5eGmUq9zpfQnyUIu5OL9iWoMMv5ZNmk3zLJ4Ep', 'api-xxxxxxxx.duosecurity.com', $requester);

    $client->apiCall('POST', '/admin/v1/users/DU1/send_sms_passcodes', []);
    $call = $requester->calls[0];

    expect($call['body'])->toBe('{}')
        ->and(ourAuthorizationFor($credentials(), $call))->toBe($call['headers']['Authorization']);
});

it('signs a DELETE with parameters, and X-Duo-* headers, as duo_api_php signs them', function () use ($credentials) {
    $requester = new RecordingRequester;
    $client = new Client('DIWJ8X6AEYOR5OMC6TQ1', 'Zh5eGmUq9zpfQnyUIu5OL9iWoMMv5ZNmk3zLJ4Ep', 'api-xxxxxxxx.duosecurity.com', $requester);
    $headers = ['X-Duo-Request-Id' => 'abc-123', 'x-duo-other' => 'two'];

    $client->apiCall('DELETE', '/admin/v1/registered_devices/blocked', ['registered_device_key_list' => '["C1","C2"]'], $headers);
    $call = $requester->calls[0];

    expect($call['url'])->toContain('registered_device_key_list=%5B%22C1%22%2C%22C2%22%5D')
        ->and(ourAuthorizationFor($credentials(), $call, $headers))->toBe($call['headers']['Authorization'])
        ->and(ourAuthorizationFor($credentials(), $call, []))->not->toBe($call['headers']['Authorization']);
});
