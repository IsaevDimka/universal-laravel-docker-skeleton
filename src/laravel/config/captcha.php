<?php

declare(strict_types=1);

return [
    'secret' => env('RECAPTCHA_SECRET'),
    'sitekey' => env('RECAPTCHA_SITE_KEY'),
    'options' => [
        'timeout' => 30,
    ],
];
