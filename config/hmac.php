<?php

return [
    'clients' => [
        'web' => env('HMAC_WEB_SECRET'),
        'mobile' => env('HMAC_MOBILE_SECRET'),
    ],
];
