<?php

namespace Testings\Api\Database;

use Testings\Api\Database\BaseDatabase;

class TestsRepository extends BaseDatabase {
	public function addNewTest( string $test_description, string $test_name )
	{
		$table_name = $this->table_names['TESTS_TABLE'];

		return $this->wpdb->query(
			$this->wpdb->prepare(
				"
		INSERT INTO $table_name
		( test_name, test_description, is_active )
		VALUES ( %s, %s, %d )
		",
				$test_name,
				$test_description,
				1
			)
		);
	}

	public function getTestsList()
	{
		$table_name = $this->table_names['TESTS_TABLE'];

		return $this->wpdb->get_results( "SELECT * FROM $table_name WHERE `archived` = 0", 'ARRAY_A' );
	}

	public function editSingleTest( int $test_id, string $test_name, string $test_description, int $is_test_active )
	{
		$table_name = $this->table_names['TESTS_TABLE'];

		return $this->wpdb->update( $table_name, [
			'test_name'        => $test_name,
			'test_description' => $test_description,
			'is_active'        => (int) $is_test_active
		], [ 'test_id' => (int) $test_id ], [ '%s', '%s', '%d' ], [ '%d' ] );
	}

	public function removeSingleTest( int $test_id )
	{
		$table_name = $this->table_names['TESTS_TABLE'];

		return $this->wpdb->update( $table_name, [
			'archived' => 1
		], [ 'test_id' => (int) $test_id ], [ '%d' ], [ '%d' ] );
	}
}