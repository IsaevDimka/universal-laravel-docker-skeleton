<?php

declare(strict_types=1);

namespace Logger;

use Monolog\Logger;

class MongoDBLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(): Logger
    {
        $handler = new MongoDBHandler();
        return new Logger('mongodb', [$handler]);
    }
}
