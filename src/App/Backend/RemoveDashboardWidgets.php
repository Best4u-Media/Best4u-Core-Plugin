<?php

namespace Best4u\Core\App\Backend;

use Best4u\Core\Common\Abstracts\Base;

class RemoveDashboardWidgets extends Base
{
	public function init()
	{
		add_action(
			'wp_dashboard_setup',
			[$this, 'removeDashboardWidgets'],
			PHP_INT_MAX
		);
		add_action(
			'wp_dashboard_setup',
			[$this, 'removeDasboardWidgetsForNonBest4u'],
			PHP_INT_MAX
		);
	}

	public function removeDashboardWidgets()
	{
		global $wp_meta_boxes;

		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	}

	public function removeDasboardWidgetsForNonBest4u()
	{
		if (get_current_user_id() === 1) {
			return;
		}

		global $wp_meta_boxes;

		// prettier-ignore
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health']);
	}
}
