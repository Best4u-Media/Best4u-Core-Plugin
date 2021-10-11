<?php

namespace Best4u\Core\Plugin\Common\Utils;

class SchemaColumn
{
	protected $title;
	protected $type = 'varchar(255)';
	protected $nullable = false;
	protected $autoIncrement = false;
	protected $default;

	public function __construct(string $title)
	{
		$this->title = $title;

		return $this;
	}

	public function type(string $type): SchemaColumn
	{
		$this->type = $type;

		return $this;
	}

	public function nullable(): SchemaColumn
	{
		$this->nullable = true;

		return $this;
	}

	public function autoIncrement(): SchemaColumn
	{
		$this->autoIncrement = true;

		return $this;
	}

	public function default(mixed $default): SchemaColumn
	{
		$this->default = $default;

		return $this;
	}

	public function getSql(): string
	{
		$sql = "{$this->title} {$this->type} ";

		if (isset($this->default)) {
			$sql .= "DEFAULT '{$this->default}' ";
		}

		if (!$this->nullable) {
			$sql .= 'NOT NULL ';
		} else {
			$sql .= 'NULL ';
		}

		if ($this->autoIncrement) {
			$sql .= 'AUTO_INCREMENT';
		}

		return $sql;
	}
}
