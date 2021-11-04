<?php

namespace Best4u\Core\App\Backend;

use Best4u\Core\Common\Abstracts\Base;
use Best4u\Core\Common\Utils\UserIdentifier;
use Best4u\Core\Common\Utils\AdminPage;
use Best4u\Core\Common\Utils\Assets;

class PluginPage extends Base
{
	public $pageHook;

	public function init()
	{
		$this->pageHook = $this->addAdminPageForBest4u();

		add_action('admin_print_scripts-' . $this->pageHook, [
			$this,
			'enqueueAssets',
		]);
	}

	public function addAdminPageForBest4u()
	{
		if (!UserIdentifier::currentUserIsBest4u()) {
			return;
		}

		$this->pageHook = AdminPage::add([
			'page_title' => __('Best4u Core', 'best4u-core'),
			'menu_title' => __('Best4u Core', 'best4u-core'),
			'position' => 3,
			'callback' => [$this, 'renderContents'],
		]);
	}

	public function renderContents()
	{
		echo '<div id="best4u-core-plugin"></div>';
	}

	public function enqueueAssets()
	{
		Assets::enqueueScript('best4u-core-backend', [
			'source' => $this->plugin->url('assets/dist/js/backend.js'),
			'dependencies' => [
				'wp-api',
				'wp-i18n',
				'wp-components',
				'wp-element',
			],
		]);

		Assets::enqueueStyle('best4u-core-backend', [
			'source' => $this->plugin->url('assets/dist/css/backend.css'),
			'dependencies' => ['wp-components'],
		]);
	}
}
