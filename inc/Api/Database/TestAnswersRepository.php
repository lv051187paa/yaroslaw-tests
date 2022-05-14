<?php

namespace Testings\Api\Database;

use Exception;

class TestAnswersRepository extends BaseDatabase {
	public function addNewAnswer( int $user_id, int $test_id, int $question_id, string $option_id_list ): int
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		$result     = $this->wpdb->query(
			$this->wpdb->prepare(
				"
		INSERT INTO $table_name
		( test_id, question_id, selected_options, user_id )
		VALUES ( %d, %d, %s, %d )
		",
				$test_id,
				$question_id,
				$option_id_list,
				$user_id
			)
		);

		return $this->wpdb->insert_id;
	}

	public function editSingleAnswer( int $user_id, int $test_id, int $question_id, string $option_id_list ): bool
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		$result     = $this->wpdb->update(
			$table_name,
			[ "selected_options" => $option_id_list ],
			[ 'question_id' => $question_id, 'test_id' => $test_id, 'user_id' => $user_id, 'completion_date' => null ],
			[ '%s', ],
			[ '%d', '%d', '%d' ]
		);

		return (bool) $result;
	}

	public function getSingleAnswer( int $user_id, int $test_id, int $question_id )
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		$query      = "SELECT selected_options FROM $table_name WHERE test_id = %d AND question_id = %d AND user_id = %d AND completion_date IS NULL";
		$result     = $this->wpdb->get_row(
			$this->wpdb->prepare(
				$query,
				$test_id,
				$question_id,
				$user_id
			)
		);

		return $result;
	}

	public function getAnswerListByTestAndUserId( int|null $user_id, int $test_id ): array|null
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		if ( isset( $user_id ) ) {
			$query  = "SELECT selected_options, question_id FROM $table_name WHERE test_id = %d AND user_id = %d AND completion_date IS NULL";
			$result = $this->wpdb->get_results(
				$this->wpdb->prepare(
					$query,
					$test_id,
					$user_id,
				), "ARRAY_A"
			);

			return $result;
		}

		$query  = "SELECT selected_options, question_id FROM $table_name WHERE test_id = %d AND completion_date IS NULL";
		$result = $this->wpdb->get_results(
			$this->wpdb->prepare(
				$query,
				$test_id,
			), "ARRAY_A"
		);

		return $result;

	}

	public function updateCompletionDate( int $user_id, int $test_id ): bool
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		$result     = $this->wpdb->update(
			$table_name,
			[ "completion_date" => current_time( 'mysql', true ) ],
			[ 'user_id' => $user_id, 'test_id' => $test_id, 'completion_date' => null ],
			[],
			[ '%d', '%d' ]
		);

		if ( $this->wpdb->last_error ) {

			throw new Exception( $this->wpdb->last_error, 500 );
		}

		return (bool) $result;
	}

	public function getFinishedTestsByUser( int $user_id )
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		// select all answers
		$query = "
				SELECT 
					t.test_name, a.completion_date, u.user_name, a.selected_options, q.question_text, a.user_id as user_code
				FROM `wp_yaroslaw_tests_tests_answers` a
				JOIN wp_yaroslaw_tests_list t ON t.test_id = a.test_id
				JOIN wp_yaroslaw_tests_users u ON u.id = a.user_id
				JOIN wp_yaroslaw_tests_questions q ON q.id = a.question_id
				WHERE user_id = 16 AND a.test_id = 12
				# GROUP BY completion_date";
	}
}