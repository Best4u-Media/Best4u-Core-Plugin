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
