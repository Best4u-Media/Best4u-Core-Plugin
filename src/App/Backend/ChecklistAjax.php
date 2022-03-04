<?php

namespace Best4u\Core\App\Backend;

use Best4u\Core\Common\Abstracts\Base;
use Best4u\Core\Common\Utils\UserIdentifier;

class ChecklistAjax extends Base
{
	public function init()
	{
		add_action('wp_ajax_best4u-core-get-checklist', [
			$this,
			'handleAjaxCall',
		]);
	}

	public function handleAjaxCall()
	{
		header('Content-Type: application/json; charset=utf-8');

		if (!UserIdentifier::currentUserIsBest4u()) {
			echo json_encode([
				'error' => __(
					'You don\'t have permission to access the checklist',
					'best4u-core'
				),
			]);

			wp_die();
			return;
		}

		$checklist = [
			'visible_to_search_engines' => [
				'label' => __('Visible to search engines?', 'best4u-core'),
				'result' => absint(get_option('blog_public', 0)) === 1,
			],
			'has_favicon' => [
				'label' => __('Site has favicon', 'best4u-core'),
				'result' => absint(get_option('site_logo', 0)) !== 0,
			],
		];

		echo json_encode([
			'error' => false,
			'checklist' => $checklist,
		]);

		wp_die();
		return;
	}
}
