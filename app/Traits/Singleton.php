<?php

namespace App\Traits;

trait Singleton
{
    /**
     * Parent instance so we use object as a reference pointer
     * @var object|null
     */
    private static ?object $instance = null;
    
    /**
     * Returns a static instance of the parent class
     * @return object
     */
    public static function getInstance(): object
    {
        return (self::$instance) ? self::$instance : (self::$instance = new self());
    }
    
    protected function __construct() {}
    private function __clone() {}
}