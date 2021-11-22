<?php

namespace App\Console;

use App\Traits\Singleton;
use App\Database\Database;
use App\Console\Controllers\BasketController;
use App\Config\Config;

final class Console
{
    use Singleton;
    
    private ?BasketController $basketController = null;
    private ?Database $database = null;
    
    protected function __construct()
    {
        (new Config(ROOT_DIR . '.env'))->load();
        error_reporting(getenv('DEBUG') ? E_ALL : 0);
        $this->database = (new Database)->configure();
        $this->basketController = new BasketController();
    }
    
    /**
     * Process the first argument as the command
     * @param type $argv
     * @return mixed
     */
    public function processCommand($argv): void
    {
        switch($argv[1] ? $argv[1] : null)
        {
            case 'MigrateDatabase':
                $this->database->migrate();
                break;
            case 'PriceBasket':
                $this->processOrder($argv);
                break;
            default:
                echo 'Usage: php -q index.php <command> <argv>';
                break;
        }
    }
    
    /**
     * Process the command line arguments
     * @param string $argv
     */
    public function processOrder(array $argv): void
    {
        $this->basketController
            ->validate(array_slice($argv, 2))
            ->processOrder();
    }
}