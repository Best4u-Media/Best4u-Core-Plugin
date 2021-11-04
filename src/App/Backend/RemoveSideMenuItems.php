<?php

namespace Best4u\Core\App\Backend;

use Best4u\Core\Common\Abstracts\Base;
use Best4u\Core\Common\Utils\UserIdentifier;

class RemoveSideMenuItems extends Base
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

		// tools
		remove_menu_page('tools.php');
		// options
		remove_menu_page('options-general.php');

		// gravityforms settings
		remove_submenu_page('gf_edit_forms', 'gf_settings');
		// blocksy blocksy
		remove_submenu_page('ct-dashboard', 'ct-dashboard');
		// blocksy account
		remove_submenu_page('ct-dashboard', 'ct-dashboard-account');
		// themes
		remove_submenu_page('themes.php', 'themes.php');
		// add plugin
		remove_submenu_page('plugins.php', 'plugin-install.php');
	}
}
