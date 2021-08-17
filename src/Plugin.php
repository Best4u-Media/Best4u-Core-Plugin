<?php

namespace Best4u\Core;

use Best4u\Core\Common\Abstracts\Base;
use Best4u\Core\Common\Utils\Context;
use Best4u\Core\Config\I18n;

class Plugin extends Base
{
    protected static $instance = null;

    protected $i18n;

    public function __construct(string $main_file)
    {
        Context::init($main_file);
    }

    public function register()
    {
        $this->i18n = I18n::getInstance();
    }

    public static function instance(): Plugin
    {
        return static::$instance;
    }

    public static function load(string $main_file): bool
    {
        if (static::$instance !== null) {
            return false;
        }

        static::$instance = new static($main_file);
        static::$instance->register();

        return true;
    }
}
