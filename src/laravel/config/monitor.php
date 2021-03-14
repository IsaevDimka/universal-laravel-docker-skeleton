<?php

declare(strict_types=1);

return [
    /**
     * Thresholds for disk space's alert.
     */
    'diskspace_percentage_threshold' => [
        'warning' => 80,
        'fail' => 90,
    ],

    'cpu_usage_percentage_threshold' => [
        'warning' => 70,
        'fail' => 90,
    ],

    'check_certificates' => [
        'sites' => [],
        /**
         * Determining if the certificate is still valid until a given date
         */
        'expiration_days' => 7,
    ],
];
