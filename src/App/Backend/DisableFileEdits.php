<?php

namespace Best4u\Core\App\Backend;

use Best4u\Core\Common\Abstracts\Base;

class DisableFileEdits extends Base
{
    public function init()
    {
        add_action('admin_menu', [$this, 'removeItemsFromMenuForNonBest4u'], PHP_INT_MAX);
    }

    public function removeItemsFromMenuForNonBest4u()
    {
        if (get_current_user_id() === 1) {
            return;
        }

        remove_submenu_page('themes.php', 'theme-editor.php');
        remove_submenu_page('plugins.php', 'plugin-editor.php');
    }
}
