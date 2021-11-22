<?php

namespace App\Traits;

trait Singleton
{
    private static ?object $instance = null;
    
    public static function getInstance(): object
    {
        return (self::$instance) ? self::$instance : (self::$instance = new self());
    }
    
    protected function __construct() {}
    private function __clone() {}
}