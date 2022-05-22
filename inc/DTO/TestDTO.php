<?php

namespace Testings\DTO;

class TestDTO {
	public int $test_id;

	public string $test_name;

	public string|null $test_description;

	public int|null $is_active;

	public int|null $archived;

	public int|null $test_users;

	public int|null $test_completed_counter;

	public function __construct( $raw_test_item )
	{
		$this->test_id                = (int) $raw_test_item->test_id;
		$this->test_name              = $raw_test_item->test_name;
		$this->test_description       = property_exists( $raw_test_item, "test_description" ) ? $raw_test_item->test_description : null;
		$this->is_active              = property_exists( $raw_test_item, "is_active" ) ? (int) $raw_test_item->is_active : null;
		$this->archived               = property_exists( $raw_test_item, "archived" ) ? (int) $raw_test_item->archived : null;
		$this->test_completed_counter = property_exists( $raw_test_item, "test_completed_counter" ) ? (int) $raw_test_item->test_completed_counter : null;
		$this->test_users             = property_exists( $raw_test_item, "test_users" ) ? (int) $raw_test_item->test_users : null;
	}
}