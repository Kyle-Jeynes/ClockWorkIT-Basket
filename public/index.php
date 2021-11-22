<?php

define('ROOT_DIR', dirname(dirname(__FILE__)) . '/');

require_once ROOT_DIR . 'vendor/autoload.php';

\App\Console\Console::getInstance()
        ->processCommand($argv);