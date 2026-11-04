![Banner](https://banners.beyondco.de/Duo%20Security.png?theme=dark&packageManager=composer+require&packageName=spkm%2Fduo-security&pattern=architect&style=style_2&description=&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

[![Tests](https://github.com/spkm/duo-security/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/spkm/duo-security/actions/workflows/tests.yml?branch=main)
[![Latest version](https://img.shields.io/packagist/v/spkm/duo-security)](https://packagist.org/packages/spkm/duo-security)
[![PHP version](https://img.shields.io/packagist/php-v/spkm/duo-security)](https://packagist.org/packages/spkm/duo-security)

[Duo Security](https://duo.com)'s [Admin API](https://duo.com/docs/adminapi) and [Auth API](https://duo.com/docs/authapi)
for Laravel, built on [Saloon](https://docs.saloon.dev).

Every endpoint in Duo's documentation is covered (184 at the time of writing) and grouped the way the docs group them:
`DuoSecurity::users()`, `DuoSecurity::phones()`, `DuoSecurity::logs()` and so on, with `DuoSecurity::auth()` for the
Auth API. Responses come back as typed models, lists and logs page themselves, and request signing is handled for you.

```php
use spkm\DuoSecurity\Facades\DuoSecurity;

$user = DuoSecurity::users()->findByUsername('jsmith');                     // User or null
$user = DuoSecurity::users()->find($userId);                                // User or null

$codes = DuoSecurity::users()->createUserBypassCodes($user->user_id, [      // a Collection of the codes
    'count' => 1, 'reuse_count' => 0, 'valid_secs' => 86400,
]);

$events = DuoSecurity::logs()->getAuthenticationLogs()
    ->since(now()->subDay())
    ->withParameter('results', 'denied')
    ->lazy();                                                                // AuthenticationLogEvent, a page at a time

$result = DuoSecurity::auth()->preauth(['username' => 'jsmith', 'ipaddr' => $request->ip()]);
if ($result->result === 'auth') {
    $outcome = DuoSecurity::auth()->auth(['username' => 'jsmith', 'factor' => 'push', 'device' => 'auto']);
}
```

## Requirements

- PHP 8.5
- Laravel 12.39 or higher, or Laravel 13
- A Duo account with an **Admin API** application (Applications > Protect an Application > Admin API in the Duo Admin
  Panel), which gives an integration key, a secret key and an API hostname. Grant it only the permissions the
  integration needs (Grant read resource, Grant write resource, Grant read log...); Duo answers a call it doesn't
  allow with 403. An **Auth API** application, with its own keys, is needed only for `DuoSecurity::auth()`.

## Installation

```bash
composer require spkm/duo-security
php artisan duo-security:install
```

The install command publishes `config/duo-security.php`. Set the Admin API application's keys in `.env`:

```dotenv
DUO_INTEGRATION_KEY=DIXXXXXXXXXXXXXXXXXX
DUO_SECRET_KEY=your-secret-key
DUO_API_HOSTNAME=api-xxxxxxxx.duosecurity.com
```

And the Auth API application's, when you use it (the hostname is the account's, so it is the one above unless set):

```dotenv
DUO_AUTH_INTEGRATION_KEY=DIXXXXXXXXXXXXXXXXXX
DUO_AUTH_SECRET_KEY=your-auth-secret-key
```

## Usage

### Two ways to call Duo

**Resources** give a method for every endpoint in a section of Duo's documentation. They return models and throw a
`DuoApiException` when Duo answers with an error:

```php
$phone = DuoSecurity::phones()->getPhone($phoneId);                                  // Phone
$group = DuoSecurity::groups()->createGroup('Staff', ['desc' => 'All staff']);       // Group
DuoSecurity::users()->addUserToGroup($user->user_id, $group->group_id);              // true
$summary = DuoSecurity::info()->getAccountSummary();                                 // AccountSummary
```

**Requests** are plain Saloon classes, one per endpoint, for when you want the response itself:

```php
use spkm\DuoSecurity\Admin\Requests\Users\GetUserRequest;

$response = DuoSecurity::send(new GetUserRequest($userId));
$user = $response->dto();                                                            // User
$raw = $response->json('response');                                                  // the record as Duo sent it
```

[ENDPOINTS.md](ENDPOINTS.md) lists every operation with its resource method and request class. A method's arguments
are the endpoint's path parameters, then an array of the parameters Duo documents (listed in each method's docblock):

```php
DuoSecurity::users()->modifyUser($userId, ['status' => 'bypass', 'notes' => 'Lost phone']);
DuoSecurity::phones()->createPhoneActivationUrl($phoneId, ['valid_secs' => 3600, 'install' => 1]);
DuoSecurity::policies()->calculatePolicy($userId, $integrationKey);                  // PolicyCalculation
```

A parameter Duo takes as a list (`user_id_list`, `username_list`, `group_id_list`, `registered_device_key_list`, the
`users` of a bulk create, the `operations` of `DuoSecurity::bulk()`) can be given as a PHP array; it is sent as the
JSON string Duo reads, as Duo's own clients send it. A date given for a time parameter is sent as the Unix timestamp
the endpoint takes.

### Lists and logs

A list method gives a `ListQuery`: `get()` for one page, `first()` for its first record, `all()` or `lazy()` for every
record (paged by `limit` and `offset` until Duo gives no next offset), `count()` for how many there are, and
`paginate()` for the pages themselves. Duo's lists filter only by the few named parameters each documents, which the
list method takes:

```php
$page = DuoSecurity::users()->listUsers()->limit(50)->get();                         // Collection<User>
$all = DuoSecurity::phones()->listPhones()->lazy();                                  // LazyCollection<Phone>
$byNumber = DuoSecurity::phones()->listPhones(['number' => '+441234567890'])->first();
$count = DuoSecurity::tokens()->listTokens()->count();                               // from Duo's total, or by reading every page

foreach (DuoSecurity::users()->listUserPhones($userId)->paginate() as $response) {
    $response->dto();                                                                // one page of Phone
}
```

The users, phones, tokens and groups resources also have finders: `find($id)` (null when Duo has none),
`findByUsername()`, `findByEmail()`, `findByNumber()` and `findBySerial()`.

A log method gives a `LogQuery`: the window with `between($from, $to)` or `since($from)` (which ends two minutes ago,
the latest Duo makes available), the order with `oldestFirst()` or `newestFirst()`, the endpoint's filters with
`withParameter()`, then `get()`, `all()` or `lazy()`. The v2 logs (authentication, authorization, activity,
telephony) page by the cursor Duo gives; the v1 logs (administrator, offline enrollment) take only a start time, so
the package asks again from the last event's time and stops at the window's end itself:

```php
$logs = DuoSecurity::logs();

$denied = $logs->getAuthenticationLogs()->since(now()->subHours(6))->withParameter('results', 'denied')->all();
$changes = $logs->getActivityLogs()->between($from, $to)->oldestFirst()->lazy();
$calls = $logs->getTelephonyLogs()->since($lastRun)->get();
```

Duo keeps 180 days of events and asks for logs to be polled no more than once a minute; a request that asks too often
answers 429 (a `RateLimitedException`).

### Models

A model is a readonly object with Duo's own field names and types: `$user->username`, `$user->is_enrolled`,
`$user->created` (a `CarbonImmutable`, from Duo's Unix timestamp), `$user->phones` (a `Collection<Phone>`). Duo
adds fields to its records without notice, so what a model has no property for is kept in `$extra` and written back
with the rest by `toArray()` and `json_encode()`. `Model::from($record)` builds one from a record as Duo sends it.

### Writing

A create or modify method takes the parameters Duo documents (any further ones are sent too) and returns the record as
stored; a delete or an operation returns `true` once Duo said it succeeded:

```php
$user = DuoSecurity::users()->createUser(['username' => 'jsmith', 'realname' => 'Jane Smith', 'email' => 'jsmith@example.com']);
DuoSecurity::users()->deleteUser($user->user_id);                                    // true
DuoSecurity::tokens()->resyncToken($tokenId, '123456', '234567', '345678');          // true
```

Bypass codes come back as the codes themselves, a `Collection` of strings; `createUserBypassCodes()` replaces the
user's existing codes unless `preserve_existing` is set, as Duo documents.

### Errors

Every error Duo answers with is a `DuoApiException` (a Saloon `RequestException`, so `getResponse()` has the answer),
with a subclass for the ones callers handle differently:

| Exception | When |
|---|---|
| `NotFoundException` | 404: no record at the path (`find()` and the other finders give null instead) |
| `UnauthorizedException` | 401: the keys, signature or date refused; 403: the application lacks the permission, or is of the wrong type |
| `RateLimitedException` | 429: too many calls of this kind; `retryAfter()` when Duo says how long to wait |
| `InvalidRequestException` | 400 and the other client errors: a parameter missing or invalid, a duplicate, an operation that failed |
| `ServerErrorException` | 5xx, a 408, or a 2xx that isn't the OK envelope Duo always sends |

A `FAIL` envelope Duo sends with a 200 is an error too, by its code, on every path: a resource method, `dto()`,
`$response->throw()` and `$response->failed()` agree, and nothing is cached or retried as a success.

```php
try {
    DuoSecurity::users()->createUser(['username' => 'jsmith']);
} catch (InvalidRequestException $exception) {
    $exception->getMessage();        // Duo answered 400 to POST /admin/v1/users: Username already exists (username)
    $exception->duoCode();           // 40003
    $exception->messageDetail();     // username
}
```

### How requests are signed

Duo signs every request rather than sending a token: the `Authorization` header is HTTP basic auth whose username is
the integration key and whose password is an HMAC-SHA512, keyed with the secret key, of a canonical description of
the request (version 5 of Duo's signing specification: the date, method, host, path, sorted query string, a SHA-512 of
the body and a SHA-512 of any `X-Duo-*` headers). Duo verifies it against the bytes it received, so the package
signs the PSR-7 request once Saloon has built it, from the query string and body about to be sent, and writes the
query string in the canonical form itself (`DuoConnector::handlePsrRequest()`). The algorithm is in
`spkm\DuoSecurity\Signing\DuoSigner`, pure and documented line by line; `DuoAuthenticator` dates the request and
applies it.

The suite checks the signer against the worked examples in Duo's documentation, the vectors in Duo's own
`duo_api_php` client, and that client itself (a development dependency only): every request shape is signed by both
and the signatures compared. The secret key is held beside the credentials rather than in them, so no dump (`dd()`,
`var_export`, a cast to array) of a connector or of an exception that reaches it can show the key; the models'
secrets (an integration's `secret_key`, an enrollment's activation code, a trusted device token) are left out of
`toArray()`, JSON and dumps unless asked for with `toArray(withHidden: true)`. Should Duo publish a new signing version, change `DuoSigner` (its `VERSION` and the
lines it describes) and add the new vectors to `tests/Fixtures/signing-vectors.json` beside the current ones; when a
signature is refused (401, code 40103), Duo's [HMAC Debugger](https://duosecurity.github.io/duo_hmac_debugger/) shows
what it computed. The clock matters too: Duo refuses a request dated more than five minutes from its own.

### The Auth API

`DuoSecurity::auth()` has the Auth API's eight endpoints, signed with the Auth API application's keys. The usual flow:
`preauth()` says whether the user may authenticate (`auth`, `allow`, `deny` or `enroll`) and lists their devices;
`auth()` sends the push, checks the passcode or makes the call, and answers `allow` or `deny`; with `async => 1` it
answers at once with a `txid` for `authStatus()` to long-poll (the connector waits at least 70 seconds for it):

```php
$auth = DuoSecurity::auth();

$auth->ping();                                                                       // ServerTime (the one unsigned call)
$auth->check();                                                                      // ServerTime, or an UnauthorizedException

$preauth = $auth->preauth(['username' => 'jsmith', 'ipaddr' => $ip]);                // PreauthResult
$started = $auth->auth(['username' => 'jsmith', 'factor' => 'push', 'device' => 'auto', 'async' => 1]);

do {
    $status = $auth->authStatus($started->txid);                                     // AuthStatus
} while ($status->result === 'waiting');

$enrollment = $auth->enroll(['username' => 'new.user']);                             // Enrollment: a QR code and a link
$auth->enrollStatus($enrollment->user_id, $enrollment->activation_code);             // success, invalid or waiting
```

### Several accounts

Another Duo account, or other applications in the same one, go under `duo-security.connections` and are reached with
`DuoSecurity::connection('name')` for the Admin API and `DuoSecurity::auth('name')` for the Auth API:

```php
DuoSecurity::connection('subsidiary')->users()->listUsers()->all();
DuoSecurity::auth('subsidiary')->preauth(['username' => 'jsmith']);
```

A subaccount of an MSP account is reached the same way, with its own API hostname (`DuoSecurity::subaccounts()` lists
them).

### Caching, timeouts and retries

`config/duo-security.php` sets the connect and request timeouts, how many times a request that changes nothing is
sent again when Duo can't be reached, answers 429 or fails on its side (`DUO_RETRIES`, each wait twice the last), and
whether the answers to GET requests are cached (`DUO_CACHE`, per account and application). A write that would rather
be repeated than lost when the connection drops before Duo answers can ask for that:

```php
DuoSecurity::send((new CreateUserBypassCodesRequest($userId, ['count' => 1]))->retryingDroppedConnections());
```

Anything the resources don't cover can be called by path, from the root of the API hostname. A value that goes in
the path is given for a `{placeholder}`, never written into the path, so it is encoded and can't carry the request
somewhere else:

```php
DuoSecurity::get('/admin/v1/users', ['username' => 'jsmith'])->json('response');
DuoSecurity::post('/admin/v1/users/{id}/bypass_codes', ['count' => 1], ['id' => $userId]);
```

The cache, when on, holds only reads of records that don't change under the reader (users, groups, phones, tokens,
policies, settings and the like), never a secret, a status being polled, a report, a log or the Auth API; entries are
JSON, keyed with the application's keys, so a connector with other keys is never served another's answers.

The facade's `send()`, `get()` and the rest go to the Admin API; an Auth API request sent that way is refused rather
than signed with the wrong keys (`DuoSecurity::authConnector()->send()` takes it). The service is a singleton, so
the connectors built from config live for the process: a long-running worker that changes `duo-security` config at
runtime should build its own `AdminConnector` from a `Credentials`.

A connection that can't be made or times out surfaces as Saloon's `FatalRequestException`, not a `DuoApiException`
(which is Duo's answer); catch both where the distinction matters. Applications that add Saloon's Laravel plugin and
Telescope should hide the parameters that carry secrets (`passcode`, `codes`, `secret`, `trusted_device_token`), as
Telescope records client requests.

## Testing

```bash
composer test
```

The suite fakes Duo with Saloon's `MockClient`; nothing reaches a real account. It checks every request against the
method and path Duo documents, signs every one and recomputes the signature from the bytes sent, builds every model,
and calls every resource method. The signing vectors and the parity test against `duo_api_php` are in
`tests/Feature/SignerTest.php` and `tests/Feature/SigningParityTest.php`.

## Contributing

Pull requests are welcome. Run `composer format`, `composer analyse` and `composer test` before opening one.

## Security

If you discover a security issue, email hello@spkmitchell.co.uk rather than opening an issue.

## Credits

- [Simon Mitchell](https://github.com/spkm)
- [Saloon](https://docs.saloon.dev)
- The parameter encoding and the rules for signed `X-Duo-*` headers follow Duo's own
  [duo_api_php](https://github.com/duosecurity/duo_api_php) (Copyright (c) 2022 Cisco Systems, Inc. and/or its
  affiliates, BSD 3-Clause: see [LICENSE-duo_api_php](LICENSE-duo_api_php)). This package is not endorsed by Duo or
  Cisco.

## Licence

MIT.
