<?php

namespace App\Database;

use PDO;
use PDOException;

class Database extends PDO
{
    private static ?object $database = null;
    
    public function __construct() {}
    
    /**
     * Connect to the Database using the PDO driver
     * @throws PDOException
     */
    public function configure(): Database
    {
        try
        {
            parent::__construct(
                "mysql:host={$this->config('MYSQL_HOST')};dbname={$this->config('MYSQL_DBMS')};charset=utf8mb4",
                $this->config('MYSQL_USER'),
                $this->config('MYSQL_PASSWORD'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
                
            return (static::$database = $this);
        }
        catch (PDOException $e)
        {
            # Log the error
            file_put_contents(ROOT_DIR . $this->config('LOG_FILE'), file_get_contents(ROOT_DIR . $this->config('LOG_FILE') . "\n{$e->getMessage()}"));
            if($this->config('DEBUG')) throw $e;
        }
    }
    
    /**
     * Queury
     * @param mixed $columns
     */
    public static function raw()
    {
        return self::$database;
    }
    
    /**
     * Only use once for Proof of concept
     * @throws PDOException
     */
    public function migrate()
    {
        # Loop through the directory for .php files
        foreach(glob(ROOT_DIR . 'app/Database/migrations/*.php', GLOB_BRACE) as $file)
        {
            try
            {
                echo 'Mirgating ' . sprintf('%s', ($exp = explode('_', $file))[count($exp) -1]) . '......';
                foreach((require $file) as $query)
                {
                    # Blind execute the SQL query
                    echo ($this->prepare($query))->execute() ? "" : "failed.\n";
                }
                echo "done.\n";
            }
            catch (PDOException $e)
            {
                # Log the error
                file_put_contents(ROOT_DIR . $this->config('LOG_FILE'), file_get_contents(ROOT_DIR . $this->config('LOG_FILE') . "\n{$e->getMessage()}"));
                if($this->config('DEBUG')) throw $e;
            }
        }
    }
    
    /**
     * Return env values
     * @param string $key
     * @return type
     */
    private function config(string $key)
    {
        # Makes string concatenation easier using braces with {$this} rather than .
        return getenv($key);
    }
}
