<?php

namespace Best4u\Core\Config;

use Best4u\Core\Common\Abstracts\Base;

class I18n extends Base
{
    public function init()
    {
        add_action('init', [$this, 'load']);
    }

    public function load()
    {
        load_plugin_textdomain(
            'best4u-core',
            false,
            dirname(plugin_basename($this->context->file()))
        );
    }
}
