<?php

namespace Best4u\Core\Plugin\Common\Utils;

use Best4u\Core\Plugin\Common\Utils\DebugLogger;

class Schema
{
	protected $tableName = '';
	protected $columns = [];
	protected $primaryKey;

	public function __construct(string $tableName)
	{
		$this->tableName = $tableName;

		return $this;
	}

	public function addColumn($title)
	{
		$this->columns[$title] = new SchemaColumn($title);

		return $this->columns[$title];
	}

	public function primaryKey(string $key)
	{
		$this->primaryKey = $key;
	}

	public function getSql(): string
	{
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$wpdb->prefix}{$this->tableName} ";

		$sql .= '(';

		$sql .= implode(
			', ',
			array_map(function ($column) {
				return $column->getSql();
			}, $this->columns)
		);

		if (isset($this->primaryKey)) {
			$sql .= ", PRIMARY KEY ({$this->primaryKey})";
		}

		$sql .= ') ';

		$sql .= "{$charset_collate};";

		return $sql;
	}
}
