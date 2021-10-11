<?php

namespace Best4u\Core\Plugin\Common\Abstracts;

use Best4u\Core\Plugin\Config\Plugin;

abstract class Base
{
	protected $plugin = null;

	public function __construct()
	{
		$this->plugin = Plugin::init();
	}
}
