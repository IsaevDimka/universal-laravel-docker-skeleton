<?php

namespace Logger;

use Monolog\Logger;

class MongoDBLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @return Logger
     */
    public function __invoke() : Logger {
        $handler = new MongoDBHandler();
        return new Logger('mongodb', [$handler]);
    }

}
