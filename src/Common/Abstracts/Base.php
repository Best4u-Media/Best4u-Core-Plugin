<?php

namespace Best4u\Core\Common\Abstracts;

use Best4u\Core\Config\Plugin;

abstract class Base
{
	protected $plugin = null;

	public function __construct()
	{
		$this->plugin = Plugin::init();
	}
}
