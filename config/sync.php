<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Local → Live post sync
    |--------------------------------------------------------------------------
    |
    | Used by `php artisan post:push` (sender, runs on your LOCAL machine) and
    | by the receiver endpoint POST /api/v1/sync/posts (runs on the LIVE site).
    |
    | SYNC_LIVE_URL   Base URL of the live site, e.g. https://careerinpak.com
    |                 (no trailing slash). Only needed on the sender side.
    |
    | SYNC_LIVE_TOKEN Personal access token issued by `php artisan post:sync-token`
    |                 on the LIVE site. Only needed on the sender side.
    |
    | SYNC_TIMEOUT    HTTP timeout in seconds for the push request.
    |
    */

    'url' => env('SYNC_LIVE_URL'),

    'token' => env('SYNC_LIVE_TOKEN'),

    'timeout' => (int) env('SYNC_TIMEOUT', 30),

    /*
    | Optional absolute path to a PEM CA bundle used to verify the live
    | site's TLS certificate. Leave empty to let the provider auto-detect
    | one (Git for Windows ships a usable ca-bundle.crt). Only needed on
    | machines where PHP's curl extension has curl.cainfo unset.
    */
    'ca_bundle' => env('SYNC_CA_BUNDLE'),

];
