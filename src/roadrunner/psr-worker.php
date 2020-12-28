<?php

ini_set('display_errors', 'stderr');

use Lib\App;

require __DIR__ . '/vendor/autoload.php';

App::instance()->start();