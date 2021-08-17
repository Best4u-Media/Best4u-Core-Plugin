<?php

namespace Best4u\Core\Common\Abstracts;

use Best4u\Core\Common\Utils\Context;

abstract class Base
{
    protected $context = null;

    public function __construct()
    {
        $this->context = Context::getInstance();

        if (function_exists($this::class . '\\init')) {
            call_user_func_array([$this, 'init']);
        }
    }
}
