<?php

declare(strict_types=1);

namespace Logger;

use Monolog\Logger;

class TelegramLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(array $config): Logger
    {
        $handler = new TelegramHandler($config, Logger::DEBUG);
        return new Logger('telegram', [$handler]);
    }
}
