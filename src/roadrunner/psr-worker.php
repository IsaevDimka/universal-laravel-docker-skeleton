<?php

declare(strict_types=1);

ini_set('display_errors', 'stderr');
date_default_timezone_set(getenv('TIMEZONE'));

use Lib\App;

require __DIR__ . '/vendor/autoload.php';

App::getInstance()->run();