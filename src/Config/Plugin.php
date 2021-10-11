<?php

namespace Best4u\Core\Config;

use Best4u\Core\Common\Traits\Singleton;

class Plugin
{
	use Singleton;

	protected static $main_file;

	public function data(): array
	{
		$plugin_data = apply_filters('best4u-core-plugin_plugin_data', [
			'plugin_template_folder' => 'templates',
			'plugin_template_cache_folder' => 'templates-cache',
		]);

		return array_merge(
			apply_filters(
				'best4u-core-plugin_plugin_meta_data',
				get_file_data(
					$this->file(),
					[
						'name' => 'Plugin Name',
						'uri' => 'Plugin URI',
						'description' => 'Description',
						'version' => 'Version',
						'author' => 'Author',
						'author-uri' => 'Author URI',
						'text-domain' => 'Text Domain',
						'domain-path' => 'Domain path',
						'required-php' => 'Requires PHP',
						'required-wp' => 'Requires WP',
						'namespace' => 'Namespace',
					],
					'plugin'
				)
			),
			$plugin_data
		);
	}

	public function setMainFile(string $main_file)
	{
		self::$main_file = $main_file;
	}

	public function file(): string
	{
		return self::$main_file;
	}

	public function directory(): string
	{
		return dirname(self::$main_file);
	}

	public function namespace(): string
	{
		return $this->data()['namespace'];
	}

	public function name(): string
	{
		return $this->data()['name'];
	}

	public function uri(): string
	{
		return $this->data()['uri'];
	}

	public function description(): string
	{
		return $this->data()['description'];
	}

	public function version(): string
	{
		return $this->data()['version'];
	}

	public function author(): string
	{
		return $this->data()['author'];
	}

	public function authorUri(): string
	{
		return $this->data()['author-uri'];
	}

	public function textDomain(): string
	{
		return $this->data()['text-domain'];
	}

	public function domainPath(): string
	{
		return $this->data()['domain-path'];
	}

	public function requiredPhp(): string
	{
		return $this->data()['required-php'];
	}

	public function requiredWp(): string
	{
		return $this->data()['required-wp'];
	}

	public function templatesPath(): string
	{
		return $this->path($this->data()['plugin_template_folder']);
	}

	public function templatesCachePath(): string
	{
		$path = $this->path($this->data()['plugin_template_cache_folder']);

		return is_writable($path) ? $path : false;
	}

	public function basename(): string
	{
		return plugin_basename(self::$main_file);
	}

	public function path(string $relative_path = '/'): string
	{
		return plugin_dir_path(self::$main_file) . ltrim($relative_path, '/');
	}

	public function url(string $relative_path = '/'): string
	{
		return plugin_dir_url(self::$main_file) . ltrim($relative_path, '/');
	}

	public function isAjax(): bool
	{
		if (wp_doing_ajax()) {
			return true;
		}

		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			strtolower(wp_unslash($_SERVER['HTTP_X_REQUESTED_WITH'])) ===
				'xmlhttprequest';
	}

	public function isAmp(): bool
	{
		return function_exists('is_amp_endpoint') && is_amp_endpoint();
	}

	public function isCron(): bool
	{
		return wp_doing_cron();
	}
}
