<?php

namespace Best4u\Core\App\Frontend;

use Best4u\Core\Common\Abstracts\Base;

class RemoveGeneratorMetaTag extends Base
{
    public function init()
    {
        remove_action('wp_head', 'wp_generator');
        
        // This also removes it from the RSS feeds
        add_filter('the_generator', [$this, 'removeVersion'], PHP_INT_MAX);
    }

    public function removeVersion()
    {
        return '';
    }
}
