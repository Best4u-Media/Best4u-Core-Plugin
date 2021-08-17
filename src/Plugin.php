<?php

namespace Best4u\Core;

use Best4u\Core\Common\Abstracts\Base;
use Best4u\Core\Common\Utils\Context;
use Best4u\Core\Config\I18n;
use Best4u\Core\Config\Updater;

class Plugin
{
    protected static $instance = null;
    protected $main_file;

    protected $i18n;
    protected $updater;

    public function __construct(string $main_file)
    {
        Context::init($main_file);
    }

    public function register()
    {
        add_action('init', function () {
            $this->i18n = new I18n();

            if (is_admin()) {
                $this->updater = new Updater();
            }

            $this->autoRunClassesInApp();
        });
    }

    protected function getFilesRecursive($target)
    {
        $files = [];

        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($target));

        foreach ($rii as $file) {
            if ($file->isDir()) {
                continue;
            }

            $files[] = $file->getPathname();
        }

        return $files;
    }

    protected function getClassesInApp()
    {
        $files = $this->getFilesRecursive(Context::getInstance()->path() . 'src/App');
        $classes = array_map(function ($file) {
            $file = str_replace(Context::getInstance()->path() . 'src/', '', $file);
            $file = str_replace('.php', '', $file);
            $file = str_replace('/', '\\', $file);

            // add the namespace
            $file = __NAMESPACE__ . '\\' . $file;

            return $file;
        }, $files);

        return $classes;
    }

    protected function autoRunClassesInApp()
    {
        $classes = $this->getClassesInApp();

        foreach ($classes as $class) {
            try {
                $object = new $class();
                $object->init();
            } catch (\Throwable $error) {
                wp_die(sprintf(__('Could not load class "%s". The "init" method is probably missing or try a `composer dumpautoload -o` to refresh the autoloader.', 'best4u-core'), $class));
            }
        }
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
