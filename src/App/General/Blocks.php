<?php

namespace Best4u\Core\App\General;

use Best4u\Core\Common\Abstracts\Base;
use Best4u\Core\Common\Utils\DebugLogger;

class Blocks extends Base
{
	public function init()
	{
		$blocks = $this->getBlockFiles();

		foreach ($blocks as $blockFile) {
			require_once $blockFile;
		}

		add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
	}

	public function enqueueScripts()
	{
		$blocks = $this->getBlockFiles();

		foreach ($blocks as $blockName => $blockFile) {
			$directory = dirname($blockFile);

			$hasFrontendScript = file_exists($directory . '/build/frontend.js');
			if (!$hasFrontendScript) {
				continue;
			}

			wp_enqueue_script(
				'best4u-blocks-' . $blockName,
				$this->plugin->url(
					'blocks/' . $blockName . '/build/frontend.js'
				),
				[],
				$this->plugin->version(),
				true
			);
		}
	}

	public function getBlockFiles(): array
	{
		$blockDirectories = $this->getSubdirectories('blocks');

		$blocksArray = [];

		foreach ($blockDirectories as $directory) {
			$directoryName = array_reverse(explode('/', $directory))[0];
			$blocksArray[$directoryName] =
				$directory . '/' . $directoryName . '.php';
		}

		return $blocksArray;
	}

	public function getSubdirectories(string $target = ''): array
	{
		$directories = glob($this->plugin->path($target . '/*'), GLOB_ONLYDIR);

		return is_array($directories) ? $directories : [];
	}
}
