<?php

namespace Testings\Api\Database;

use Testings\Api\Database\BaseDatabase;

class OptionsRepository extends BaseDatabase {
	public function getSingleOption( int $option_id )
	{
		$table_name = $this->table_names['TESTS_OPTIONS'];
		$query      = "SELECT * FROM $table_name WHERE `id` = %d";
		$request    = $this->wpdb->prepare( $query, $option_id );

		return $this->wpdb->get_results( $request, 'ARRAY_A' );


	}

	public function addNewTestOption( string $option_text, int $option_value, int $question_id )
	{
		$table_name = $this->table_names['TESTS_OPTIONS'];

		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				"
		INSERT INTO $table_name
		( option_text, option_value, question_id )
		VALUES ( %s, %d, %d )
		",
				$option_text,
				$option_value,
				$question_id
			)
		);

		return $this->wpdb->insert_id;
	}

	public function addBulkOptions( array $options_list, int $question_id )
	{
		$table_name = (string) $this->table_names['TESTS_OPTIONS'];
		$query_options = [];
		foreach ($options_list as $option_data) {
			$query_options[] = "( " . implode( ',', $option_data ) . ", $question_id )";
		}
		$query_options_text = implode(",", $query_options);
		$sql = "INSERT INTO $table_name(option_text, option_value, question_id)
				VALUES
				   $query_options_text";

		$result = $this->wpdb->query($sql);
	}

	public function editSingleOption( int $option_id, string $option_text, int $option_value )
	{
		$table_name = $this->table_names['TESTS_OPTIONS'];

		$result = $this->wpdb->update( $table_name, [
			'option_text'  => $option_text,
			'option_value' => $option_value,
		], [ 'id' => (int) $option_id ], [ '%s', '%d', ], [ '%d' ] );

		return (bool) $result;
	}

	public function removeSingleOption( int $option_id )
	{
		$table_name = $this->table_names['TESTS_OPTIONS'];

		return $this->wpdb->update( $table_name, [
			'archived'  => 1,
		], [ 'id' => (int) $option_id ], [ '%d', ], [ '%d' ] );
	}
}