<?php

# Global definition of root directory
define('ROOT_DIR', dirname(dirname(__FILE__)) . '/');

# Load with PHP-PSR4
require_once ROOT_DIR . 'vendor/autoload.php';

# Start the magic - $argv contains CLI arguments
\App\Console\Console::getInstance()
        ->processCommand($argv);