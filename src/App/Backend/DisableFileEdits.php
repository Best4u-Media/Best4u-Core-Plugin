<?php

namespace Best4u\Core\App\Backend;

use Best4u\Core\Common\Abstracts\Base;
use Best4u\Core\Common\Utils\UserIdentifier;

class DisableFileEdits extends Base
{
	public function init()
	{
		add_action(
			'admin_menu',
			[$this, 'removeItemsFromMenuForNonBest4u'],
			PHP_INT_MAX
		);
	}

	public function removeItemsFromMenuForNonBest4u()
	{
		if (UserIdentifier::currentUserIsBest4u()) {
			return;
		}

		remove_submenu_page('themes.php', 'theme-editor.php');
		remove_submenu_page('plugins.php', 'plugin-editor.php');
	}
}
