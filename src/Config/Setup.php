<?php

namespace Best4u\Core\Plugin\Config;

use Best4u\Core\Plugin\Common\Traits\Singleton;

class Setup
{
	use Singleton;

	public static function activation()
	{
		if (!current_user_can('activate_plugins')) {
			return;
		}

		flush_rewrite_rules();
	}

	public static function deactivation()
	{
		if (!current_user_can('deactivate_plugins')) {
			return;
		}

		flush_rewrite_rules();
	}

	public static function uninstall()
	{
		if (!current_user_can('activate_plugins')) {
			return;
		}
	}
}
