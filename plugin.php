<?php
/**
 * Plugin Name:		Best4u Core
 * Description:		Updatable WordPress plugin with our core functionalities
 * Version:			0.0.1
 * Author:			Best4u
 * Author URI:		best4u.nl
 * Text Domain:		best4u-core
 * Domain Path:		/languages
 */


function best4u_core_load()
{
    $composer = __DIR__ . '/vendor/autoload.php';

    if (file_exists($composer)) {
        require $composer;
    } else {
        wp_die('Run <code>composer install</code>');
    }

    if (!class_exists('Best4u\\Core\\Plugin')) {
        deactivate_plugins(plugin_basename(__FILE__));
    }

    Best4u\Core\Plugin::load(__FILE__);
}

best4u_core_load();
