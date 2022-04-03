<?php

namespace Testings\Api\Database;

use Testings\Api\Database\BaseDatabase;

class TestAnswersRepository extends BaseDatabase{
	public function addNewAnswer( int $test_id, int $question_id, string $option_id_list )
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		$result = $this->wpdb->query(
			$this->wpdb->prepare(
				"
		INSERT INTO $table_name
		( test_id, question_id, selected_options )
		VALUES ( %d, %d, %s )
		",
				$test_id,
				$question_id,
				$option_id_list
			)
		);

		return $this->wpdb->insert_id;
	}

	public function editSingleAnswer( int $test_id, int $question_id, string $option_id_list )
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		$result = $this->wpdb->update(
			$table_name,
			["selected_options" => $option_id_list],
			['question_id' => $question_id, 'test_id' => $test_id],
			[ '%s',],
			[ '%d', '%d' ]
		);

		return (bool) $result;
	}

	public function getSingleAnswer( int $test_id, int $question_id )
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		$query = "SELECT selected_options FROM $table_name WHERE test_id = %d AND question_id = %d";
		$result = $this->wpdb->get_row(
			$this->wpdb->prepare(
				$query,
				$test_id,
				$question_id
			)
		);

		return $result;
	}

	public function getAnswerListByTest( int $test_id )
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		$query = "SELECT selected_options, question_id FROM $table_name WHERE test_id = %d";
		$result = $this->wpdb->get_results(
			$this->wpdb->prepare(
				$query,
				$test_id,
			), "ARRAY_A"
		);

		return $result;
	}
}