<?php

namespace Best4u\Core\App\Frontend;

use Best4u\Core\Common\Abstracts\Base;

class RemoveVersionString extends Base
{
	public function init()
	{
		add_filter('style_loader_src', [$this, 'removeVersion'], PHP_INT_MAX);
		add_filter('script_loader_src', [$this, 'removeVersion'], PHP_INT_MAX);
	}

	public function removeVersion($src)
	{
		return remove_query_arg('ver', $src);
	}
}
