<?php

namespace Best4u\Core\Config;

use Best4u\Core\Common\Abstracts\Base;

class I18n extends Base
{
	public function load()
	{
		load_plugin_textdomain(
			$this->plugin->textDomain(),
			false,
			dirname(plugin_basename($this->plugin->file())) .
				$this->plugin->domainPath()
		);
	}
}
