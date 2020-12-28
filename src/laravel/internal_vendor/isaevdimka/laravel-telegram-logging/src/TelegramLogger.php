<?php

namespace Logger;

use Monolog\Logger;

class TelegramLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param array $config
     *
     * @return \Monolog\Logger
     */
    public function __invoke(array $config): Logger
    {
        $handler = new TelegramHandler($config, Logger::DEBUG);
        return new Logger('telegram', [$handler]);
    }
}
