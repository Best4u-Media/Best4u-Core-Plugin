<?php

namespace Best4u\Core\Common\Traits;

trait Singleton
{
    private static $instance;

    protected function __construct()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __clone()
    {
    }

    public function __sleep()
    {
    }
    
    public function __wakeup()
    {
    }
}
