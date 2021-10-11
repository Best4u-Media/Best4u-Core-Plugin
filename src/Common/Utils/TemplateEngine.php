<?php

namespace Best4u\Core\Plugin\Common\Utils;

use Best4u\Core\Plugin\Common\Abstracts\Base;
use \Twig;

class TemplateEngine extends Base
{
	protected static $loader;
	protected static $twig;

	public function __construct()
	{
		parent::__construct();

		self::$loader = new Twig\Loader\FilesystemLoader(
			$this->plugin->templatesPath()
		);
		self::$twig = new Twig\Environment(self::$loader, [
			// 'cache' => $this->plugin->templatesCachePath(),
			'auto_reload' => true,
		]);

		$this->addWpTranslateSupport();
	}

	protected function addWpTranslateSupport()
	{
		$translationFunctions = ['__', '_x', '_n', 'wp_editor', 'uniqid'];

		foreach ($translationFunctions as $translationFunction) {
			$function = new Twig\TwigFunction(
				$translationFunction,
				$translationFunction
			);

			self::$twig->addFunction($function);
		}
	}

	public static function render($templateName, array $variables = []): string
	{
		return self::$twig->render($templateName, $variables);
	}
}
