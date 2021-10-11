<?php

namespace Best4u\Core\Plugin\Common\Abstracts;

use Best4u\Core\Plugin\Common\Utils\Schema;
use Best4u\Core\Plugin\Config\Plugin;

class DatabaseTable
{
	public static $version;
	public static $name;

	protected $schema;

	public function init()
	{
		$this->install();
	}

	protected static function getVersionOptionName(): string
	{
		return Plugin::init()->textDomain() .
			'_' .
			static::$name .
			'_db_version';
	}

	public function install()
	{
		$installed_version = get_option(static::getVersionOptionName());

		if ($installed_version === static::$version) {
			return;
		}

		$this->schema = new Schema(static::$name);

		$this->schema($this->schema);

		$sql = $this->schema->getSql();

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql);

		update_option(static::getVersionOptionName(), static::$version);
	}

	public function schema(Schema &$schema)
	{
		return $schema;
	}
}
