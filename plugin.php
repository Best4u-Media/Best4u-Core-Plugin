<?php
/**
 * Plugin Name:		  Best4u Core
 * Description:		  Updatable WordPress plugin with our core functionalities
 * Version:			  0.0.3
 * Requires at least: 5.7
 * Requires PHP:      7.4
 * Author:			  Best4u
 * Author URI:		  best4u.nl
 * Text Domain:		  best4u-core
 * Domain Path:		  /languages
 * Namespace:         Best4u\Core
 */

/**
 * Creates an instance of the Bootstrap class
 *
 * @return void
 * @since 0.0.1
 */
function best4u_core_load()
{
	$composer = __DIR__ . '/vendor/autoload.php';

	if (!file_exists($composer)) {
		wp_die(
			__(
				'Composer cannot be loaded. Try running <code>composer install</code>',
				'best4u-core'
			)
		);
	}

	require $composer;

	if (!class_exists('Best4u\\Core\\Bootstrap')) {
		return deactivate_plugins(plugin_basename(__FILE__));
	}

	register_activation_hook(__FILE__, [
		'Best4u\Core\Config\Setup',
		'activation',
	]);
	register_deactivation_hook(__FILE__, [
		'Best4u\Core\Config\Setup',
		'deactivation',
	]);
	register_uninstall_hook(__FILE__, [
		'Best4u\Core\Config\Setup',
		'uninstall',
	]);

	add_action('plugins_loaded', static function () {
		try {
			new Best4u\Core\Bootstrap(__FILE__);
		} catch (\Exception $e) {
			wp_die(
				__(
					'Best4u Core is unable to run the Bootstrap class.',
					'best4u-core'
				)
			);
		}
	});
}

best4u_core_load();
