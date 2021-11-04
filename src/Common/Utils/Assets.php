<?php

namespace Best4u\Core\Common\Utils;

use Best4u\Core\Config\Plugin;

class Assets
{
	public function enqueueScript($handle, $options = [])
	{
		$defaults = [
			'dependencies' => [],
			'version' => Plugin::init()->version(),
			'in_footer' => true,
			'js_variables' => false,
		];

		$options = array_merge($defaults, $options);

		if (!isset($options['source'])) {
			return false;
		}

		wp_enqueue_script(
			$handle,
			$options['source'],
			$options['dependencies'],
			$options['version'],
			$options['in_footer']
		);

		if (!is_array($options['js_variables'])) {
			return true;
		}

		foreach ($options['js_variables'] as $name => $values) {
			wp_localize_script($handle, $name, $values);
		}

		return true;
	}

	public function enqueueStyle($handle, $options = [])
	{
		$defaults = [
			'dependencies' => [],
			'version' => Plugin::init()->version(),
			'media' => 'all',
		];

		$options = array_merge($defaults, $options);

		if (!isset($options['source'])) {
			return false;
		}

		wp_enqueue_style(
			$handle,
			$options['source'],
			$options['dependencies'],
			$options['media']
		);

		return true;
	}
}
