<?php

use spkm\DuoSecurity\Signing\Credentials;
use spkm\DuoSecurity\Signing\DuoSigner;

/**
 * @return array<string, mixed>
 */
function signingVector(string $name): array
{
    $vectors = json_decode((string) file_get_contents(__DIR__.'/../Fixtures/signing-vectors.json'), true, flags: JSON_THROW_ON_ERROR);

    return $vectors[$name];
}

it('writes the canonical string and the Authorization header Duo documents', function (string $name) {
    $vector = signingVector($name);
    $credentials = new Credentials($vector['integration_key'], $vector['secret_key'], $vector['host']);
    $query = DuoSigner::canonicalQuery($vector['query']);
    $canonical = DuoSigner::canonical($vector['date'], $vector['method'], $vector['host'], $vector['path'], $query, $vector['body'], $vector['headers']);
    $authorization = DuoSigner::sign($credentials, $vector['date'], $vector['method'], $vector['path'], $query, $vector['body'], $vector['headers']);

    if ($vector['canonical'] !== null) {
        expect($canonical)->toBe($vector['canonical']);
    }

    if ($vector['authorization'] !== null) {
        expect($authorization)->toBe($vector['authorization']);
    }

    expect($authorization)->toBe(DuoSigner::authorization($vector['integration_key'], DuoSigner::signature($vector['secret_key'], $canonical)));
})->with(['docs-post', 'docs-get', 'duo_api_php-sign-parameters']);

it('signs a canonical string as duo_api_php does', function () {
    $vector = signingVector('duo_api_php-signature');

    expect(DuoSigner::signature($vector['secret_key'], $vector['canonical']))->toBe($vector['signature']);
});

it('hashes an empty body and no headers as the SHA-512 of the empty string, and a bodiless POST as {}', function () {
    expect(DuoSigner::EMPTY_HASH)->toBe(hash('sha512', ''))
        ->and(DuoSigner::headersHash([]))->toBe(DuoSigner::EMPTY_HASH)
        ->and(explode("\n", DuoSigner::canonical('d', 'GET', 'h', '/p', '', ''))[5])->toBe(DuoSigner::EMPTY_HASH)
        ->and(explode("\n", DuoSigner::canonical('d', 'POST', 'h', '/p', '', '{}'))[5])->toBe('27c74670adb75075fad058d5ceaf7b20c4e7786c83bae8a32f626f9782af34c9a33c2046ef60fd2a7878d378e29fec851806bbd9a67878f3a9f1cda4830763fd');
});

it('uppercases the method and lowercases the host, leaving the path as it is', function () {
    $lines = explode("\n", DuoSigner::canonical('Fri, 07 Dec 2012 17:18:00 -0000', 'PoSt', 'foO.BAr52.cOm', '/Foo/BaR2/qux', 'a=b', '{}'));

    expect($lines[1])->toBe('POST')
        ->and($lines[2])->toBe('foo.bar52.com')
        ->and($lines[3])->toBe('/Foo/BaR2/qux')
        ->and($lines[4])->toBe('a=b')
        ->and($lines)->toHaveCount(7);
});

it('writes the query string sorted by key and percent-encoded as RFC 3986 says', function () {
    $query = DuoSigner::canonicalQuery([
        'username' => 'root',
        'realname' => 'First Last',
        'email' => 'a+b@example.com',
        'notes' => "tilde~dash-dot.under_ slash/ \u{e9}",
        'count' => 3,
        'preserve_existing' => true,
        'muted' => false,
        'absent' => null,
    ]);

    expect($query)->toBe('count=3&email=a%2Bb%40example.com&muted=false&notes=tilde~dash-dot.under_%20slash%2F%20%C3%A9&preserve_existing=true&realname=First%20Last&username=root');
});

it('sorts query keys byte by byte, as Duo\'s own clients do', function () {
    expect(DuoSigner::canonicalQuery(['b' => '1', 'B' => '2', 'a' => '3', '10' => '4', '9' => '5']))->toBe('10=4&9=5&B=2&a=3&b=1')
        ->and(DuoSigner::canonicalQuery([]))->toBe('');
});

it('refuses a list in the query string, which Duo takes as a JSON string instead', function () {
    expect(fn () => DuoSigner::canonicalQuery(['user_id_list' => ['DU1', 'DU2']]))->toThrow(InvalidArgumentException::class, 'user_id_list');
});

it('hashes the X-Duo-* headers lowercased, sorted by name and joined by NUL bytes, with their values trimmed', function () {
    $expected = hash('sha512', "x-duo-a\0one\0x-duo-b\0two");

    expect(DuoSigner::headersHash(['X-Duo-B' => ' two ', 'x-duo-A' => 'one']))->toBe($expected)
        ->and(DuoSigner::headersHash(['x-duo-a' => 'one', 'x-duo-b' => 'two']))->toBe($expected);
});

it('refuses to sign a header that is not an X-Duo-* one, one with a NUL byte, or one given twice', function () {
    expect(fn () => DuoSigner::headersHash(['X-Other' => 'x']))->toThrow(InvalidArgumentException::class, 'x-other')
        ->and(fn () => DuoSigner::headersHash(['X-Duo-A' => "a\0b"]))->toThrow(InvalidArgumentException::class, 'NUL')
        ->and(fn () => DuoSigner::headersHash(["X-Duo-\0A" => 'b']))->toThrow(InvalidArgumentException::class, 'NUL')
        ->and(fn () => DuoSigner::headersHash(['X-Duo-A' => '1', 'x-duo-a' => '2']))->toThrow(InvalidArgumentException::class, 'twice');
});

it('implements version 5 of Duo\'s signing specification', function () {
    expect(DuoSigner::VERSION)->toBe(5);
});

it('keeps the API hostname lowercase and bare, whatever it was configured as', function (string $configured) {
    expect((new Credentials('DIWJ8X6AEYOR5OMC6TQ1', 'secret', $configured))->apiHostname)->toBe('api-xxxxxxxx.duosecurity.com');
})->with([
    'api-xxxxxxxx.duosecurity.com',
    'API-XXXXXXXX.duosecurity.com',
    'https://api-xxxxxxxx.duosecurity.com',
    'https://api-xxxxxxxx.duosecurity.com/',
    ' https://api-xxxxxxxx.duosecurity.com/admin/v1 ',
]);

it('refuses credentials without a key or a hostname, and never shows or serialises the secret', function () {
    $credentials = new Credentials('ikey', 'skey', 'api-xxxxxxxx.duosecurity.com');

    expect(fn () => new Credentials('', 'skey', 'host'))->toThrow(InvalidArgumentException::class)
        ->and(fn () => new Credentials('ikey', ' ', 'host'))->toThrow(InvalidArgumentException::class)
        ->and(fn () => new Credentials('ikey', 'skey', ''))->toThrow(InvalidArgumentException::class)
        ->and(fn () => new Credentials('ikey', 'skey', 'https://'))->toThrow(InvalidArgumentException::class)
        ->and($credentials->identity())->toBe('ikey')
        ->and($credentials->signature('x'))->toBe(hash_hmac('sha512', 'x', 'skey'))
        ->and($credentials->__debugInfo())->toBe(['integrationKey' => 'ikey', 'secretKey' => '[redacted]', 'apiHostname' => 'api-xxxxxxxx.duosecurity.com'])
        ->and(print_r($credentials, true))->not->toContain('skey')
        ->and(fn () => serialize($credentials))->toThrow(LogicException::class);
});
