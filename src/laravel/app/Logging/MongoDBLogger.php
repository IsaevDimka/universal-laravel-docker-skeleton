<?php

namespace App\Logging;

use Monolog\Logger;

class MongoDBLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke() : Logger {
        $handler = new MongoDBHandler();
        return new Logger('mongodb', [$handler]);
    }

}
