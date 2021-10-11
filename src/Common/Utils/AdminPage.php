<?php

namespace Best4u\Core\Plugin\Common\Utils;

class AdminPage
{
	public static function add(array $options = [])
	{
		$defaults = [
			'page_title' => 'Admin Page',
			'menu_title' => 'Admin Page',
			'capability' => 'manage_options',
			'menu_slug' => 'admin-page',
			'callback' => [self::class, 'renderDefault'],
			'icon_url' => 'dashicons-admin-generic',
			'position' => 85,
			'parent_slug' => false,
		];

		$options = array_merge($defaults, $options);

		extract($options);

		if (is_string($parent_slug)) {
			return add_submenu_page(
				$parent_slug,
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback,
				$position
			);
		}

		return add_menu_page(
			$page_title,
			$menu_title,
			$capability,
			$menu_slug,
			$callback,
			$icon_url,
			$position
		);
	}

	public static function renderDefault()
	{
		ob_start(); ?>

		<h2>No callback is set</h2>

		<?php return ob_get_clean();
	}
}
