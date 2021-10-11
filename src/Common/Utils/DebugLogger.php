<?php

namespace Best4u\Core\Plugin\Common\Utils;

use Best4u\Core\Plugin\Config\Plugin;

class DebugLogger
{
	const FILE_NAME = 'log.json';

	public static function log($value)
	{
		$path = Plugin::init()->path(self::FILE_NAME);
		file_put_contents($path, json_encode($value));
	}
}
