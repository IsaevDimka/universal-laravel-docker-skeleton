<?php

declare(strict_types=1);

return [
    /*
     * The host to use when listening for debug server connections.
     */
    'host' => env('DUMP_SERVER_HOST', 'tcp://0.0.0.0:80'),
];
