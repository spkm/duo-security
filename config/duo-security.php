<?php

return [
    // The Admin API application (Applications > Protect an Application > Admin API in the Duo Admin Panel): its
    // integration key, secret key and API hostname, such as api-xxxxxxxx.duosecurity.com. Give the application
    // only the permissions (Grant read resource, Grant write resource, Grant read log, ...) the integration needs;
    // Duo answers a call it doesn't allow with 403.
    'admin' => [
        'integration_key' => env('DUO_INTEGRATION_KEY'),
        'secret_key' => env('DUO_SECRET_KEY'),
        'api_hostname' => env('DUO_API_HOSTNAME'),
    ],

    // The Auth API application (Protect an Application > Auth API), for DuoSecurity::auth(): the same shape, with
    // its own keys. The API hostname is the account's, so it is the admin one unless set.
    'auth' => [
        'integration_key' => env('DUO_AUTH_INTEGRATION_KEY'),
        'secret_key' => env('DUO_AUTH_SECRET_KEY'),
        'api_hostname' => env('DUO_AUTH_API_HOSTNAME', env('DUO_API_HOSTNAME')),
    ],

    // Other Duo accounts (or other applications in the same account), used with DuoSecurity::connection('name')
    // and DuoSecurity::auth('name'):
    // 'connections' => [
    //     'other' => [
    //         'admin' => ['integration_key' => ..., 'secret_key' => ..., 'api_hostname' => ...],
    //         'auth' => ['integration_key' => ..., 'secret_key' => ..., 'api_hostname' => ...],
    //     ],
    // ],
    'connections' => [],

    'timeouts' => [
        'connect' => env('DUO_CONNECT_TIMEOUT', 10),
        // The Auth API's /auth/v2/auth_status long-polls for up to a minute; an Auth API connector uses the larger
        // of this and 70 seconds.
        'request' => env('DUO_REQUEST_TIMEOUT', 30),
    ],

    'retries' => [
        // How many times to send a request in all when Duo can't be reached, answers 429 Too Many Requests or
        // fails on its side: GET requests only, unless a request opts in with retryingDroppedConnections(). Each
        // wait is twice the last, from interval_ms.
        'times' => env('DUO_RETRIES'),
        'interval_ms' => 1000,
    ],

    'cache' => [
        // Cache the answers to reads of records that don't change under the reader (users, groups, phones, tokens,
        // policies, settings...), per application; never a secret, a status being polled, a report, a log or the
        // Auth API. Entries are JSON, keyed with the application's keys, for cache.seconds.
        'enabled' => env('DUO_CACHE', false),
        'store' => env('DUO_CACHE_STORE'),
        'seconds' => 3600,
    ],

    'pagination' => [
        // Records per page when paging through a list with all(), lazy() or paginate(), up to what each endpoint
        // allows (100 to 1000).
        'per_page' => 100,
    ],
];
