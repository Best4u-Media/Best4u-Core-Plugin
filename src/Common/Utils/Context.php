<?php

namespace Best4u\Core\Common\Utils;

use Best4u\Core\Common\Traits\Singleton;

class Context
{
    use Singleton;

    protected static $main_file;

    public static function init(string $main_file)
    {
        self::$main_file = $main_file;
    }

    public function file(): string
    {
        return self::$main_file;
    }

    public function basename(): string
    {
        return plugin_basename(self::$main_file);
    }

    public function path(string $relative_path = '/'): string
    {
        return plugin_dir_path(self::$main_file) . ltrim($relative_path, '/');
    }

    public function url(string $relative_path = '/'): string
    {
        return plugin_dir_url(self::$main_file) . ltrim($relative_path, '/');
    }

    public function isAjax(): bool
    {
        if (wp_doing_ajax()) {
            return true;
        }

        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower(wp_unslash($_SERVER['HTTP_X_REQUESTED_WITH'])) === 'xmlhttprequest';
    }

    public function isAmp(): bool
    {
        return function_exists('is_amp_endpoint') && is_amp_endpoint();
    }

    public function isCron(): bool
    {
        return wp_doing_cron();
    }
}
