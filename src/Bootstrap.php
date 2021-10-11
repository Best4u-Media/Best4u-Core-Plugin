<?php

namespace Best4u\Core;

use Best4u\Core\Common\Abstracts\Base;
use Best4u\Core\Config\I18n;
use Best4u\Core\Config\Updater;
use Best4u\Core\Common\Utils\TemplateEngine;

/**
 * Class that bootstraps the plugin
 *
 * @since 0.0.1
 */
class Bootstrap extends Base
{
	protected $mainFile;

	protected $i18n;
	protected $updater;
	protected $templateEngine;

	protected $loadedClasses = [];

	public function __construct(string $mainFile)
	{
		parent::__construct();

		$this->plugin->setMainFile($mainFile);

		$this->register();
	}

	public function register()
	{
		$this->i18n = new I18n();
		$this->i18n->load();
		$this->templateEngine = new TemplateEngine();

		if (is_admin()) {
			$this->updater = new Updater();
		}

		$this->autoRunClassesInIntegrations();
		$this->autoRunClassesInApp();
	}

	protected function getFilesRecursive(string $target)
	{
		$files = [];

		if (!is_readable($target)) {
			return $files;
		}

		$rii = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($target)
		);

		foreach ($rii as $file) {
			if ($file->isDir()) {
				continue;
			}

			$files[] = $file->getPathname();
		}

		return $files;
	}

	protected function getClassesInDirectory(
		string $target,
		array $except = []
	): array {
		$files = $this->getFilesRecursive($target);
		$files = array_filter($files, function ($path) use ($except) {
			foreach ($except as $exceptPath) {
				if (strpos($path, $exceptPath) !== false) {
					return false;
				}
			}

			return true;
		});

		$classes = array_map(function ($file) {
			$file = str_replace($this->plugin->path() . 'src/', '', $file);
			$file = str_replace('.php', '', $file);
			$file = str_replace('/', '\\', $file);

			// add the namespace
			$file = __NAMESPACE__ . '\\' . $file;

			return $file;
		}, $files);

		return $classes;
	}

	protected function autoRunClassesInDirectory(
		string $target,
		array $except = []
	) {
		$classes = $this->getClassesInDirectory($target, $except);

		foreach ($classes as $class) {
			if (in_array($class, $this->loadedClasses)) {
				continue;
			}

			$this->loadedClasses[] = $class;

			if (property_exists($class, 'excludeFromAutoload')) {
				continue;
			}

			try {
				$object = new $class();
				$object->init();
			} catch (\Throwable $error) {
				wp_die(
					sprintf(
						__(
							'Could not load class "%s". The "init" method is probably missing or try a `composer dumpautoload -o` to refresh the autoloader.',
							'estatit-stripe-payments'
						) .
							'<br><br>' .
							$error->getMessage() .
							'<br><code>' .
							$error->getFile() .
							'</code> at line ' .
							$error->getLine(),
						$class
					)
				);
			}
		}
	}

	protected function autoRunClassesInApp()
	{
		if (is_admin()) {
			$this->autoRunClassesInDirectory(
				$this->plugin->path('src/App/Backend')
			);
		}

		if (!is_admin()) {
			$this->autoRunClassesInDirectory(
				$this->plugin->path('src/App/Frontend')
			);
		}

		return $this->autoRunClassesInDirectory(
			$this->plugin->path('src/App'),
			[
				$this->plugin->path('src/App/Backend'),
				$this->plugin->path('src/App/Frontend'),
			]
		);
	}

	protected function autoRunClassesInIntegrations()
	{
		return $this->autoRunClassesInDirectory(
			$this->plugin->path('src/Integrations')
		);
	}
}
