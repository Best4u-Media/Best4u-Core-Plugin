<?php

namespace Best4u\Core\Common\Traits;

trait Singleton
{
    private static $instance;

    protected function __construct()
    {
    }

    public function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function __clone()
    {
    }

    protected function __sleep()
    {
    }
    
    protected function __wakeup()
    {
    }
}
