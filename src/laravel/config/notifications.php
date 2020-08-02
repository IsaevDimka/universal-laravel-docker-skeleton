<?php

/**
 * Laravel notification custom config
 */

return [
    'connection'   => 'redis',
    'queue'        => 'notification',
    'curl_timeout' => 10.0,
    'channels' => [
        'mail',
        'telegram',
        'webpush',
        'broadcast',
        'sms',
        'whatsapp',
        'notification',
        'mailchimp',
    ],
];
