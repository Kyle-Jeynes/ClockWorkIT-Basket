<?php

namespace App\Config;

use InvalidArgumentException;
use RuntimeException;

class Config
{
    /**
     * Path to environmental file
     * @var string
     */
    protected string $path;
    
    /**
     * Check file exists
     * @param string $path
     * @throws InvalidArgumentException
     */
    public function __construct(string $path)
    {
        if(!file_exists($path))
        {
            throw new InvalidArgumentException(sprintf('Cannot load env file at %s: file does not exist.', $this->path));
        }
        
        $this->path = $path;
    }
    
    /**
     * Parse the file and setenv
     * @return void
     * @throws RuntimeException
     */
    public function load(): void
    {
        if(!is_readable($this->path))
        {
            throw new RuntimeException(sprintf('Fix file permissions for %s', $this->path));
        }
        
        foreach(file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line)
        {
            if(strpos(trim($line), '#') === 0) continue;
            putenv(($env = sprintf('%s=%s', ...explode('=', $line))));
        }
    }
}