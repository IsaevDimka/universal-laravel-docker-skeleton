<?php

return [
    'secret' => env('RECAPTCHA_SECRET'),
    'sitekey' => env('RECAPTCHA_SITE_KEY'),
    'options' => [
        'timeout' => 30,
    ],
];
