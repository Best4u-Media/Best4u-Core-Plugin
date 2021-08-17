<?php

namespace Best4u\Core\Config;

use Best4u\Core\Common\Abstracts\Base;
use Best4u\Core\Common\Utils\Context;

class I18n extends Base
{
    public function load()
    {
        load_plugin_textdomain(
            'best4u-core',
            false,
            dirname(plugin_basename($this->context->file()))
        );
    }
}
