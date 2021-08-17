<?php

namespace Best4u\Core\App\Backend;

use Best4u\Core\Common\Abstracts\Base;

class DisableFileEdits extends Base
{
    public function init()
    {
        add_action('admin_menu', [$this, 'removeItemsFromMenu'], PHP_INT_MAX);
    }

    public function removeItemsFromMenu()
    {
        remove_submenu_page('themes.php', 'theme-editor.php');
        remove_submenu_page('plugins.php', 'plugin-editor.php');
    }
}
