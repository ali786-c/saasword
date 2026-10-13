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

];
