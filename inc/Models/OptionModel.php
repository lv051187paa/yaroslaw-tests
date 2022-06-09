<?php

namespace Testings\Models;

class OptionModel {
	public string $option_text;

	public int $option_value;

	public function __construct($option)
	{
		$this->option_text = $option->option_text;
		$this->option_value = $option->option_value;
	}
}