<?php

namespace Testings\Api\Database;

use Exception;
use Testings\Models\AdminAnswerDataModel;

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

	public function getAnswerListByUserId( int|null $user_id ): array|null
	{
		$table_name = $this->table_names['TESTS_ANSWERS'];
		if ( ! isset( $user_id ) ) {
			throw new Exception( "Невірні дані користувача", 400 );
		}

		$query  = "SELECT selected_options, question_id, test_id FROM $table_name WHERE user_id = %d";
		$result = $this->wpdb->get_results(
			$this->wpdb->prepare(
				$query,
				$user_id,
			)
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

	public function getAnswersByParams( int|null $user_id, int|null $test_id ): array
	{

		$answers_table   = $this->table_names['TESTS_ANSWERS'];
		$tests_table     = $this->table_names['TESTS_TABLE'];
		$questions_table = $this->table_names['TESTS_QUESTIONS'];
		$options_table   = $this->table_names['TESTS_OPTIONS'];
		$users_table   = $this->table_names['TESTS_USERS'];
		$where_query = [];
		$where_prepared = [];

		if ( isset( $user_id ) ) {
			$where_query[] = "a.user_id = %d";
			$where_prepared[] = $user_id;
		}

		if ( isset( $test_id ) ) {
			$where_query[] = "a.test_id = %d";
			$where_prepared[] = $test_id;
		}

		if ( empty( $where_query ) ) {

			$where_query_text = "WHERE a.completion_date IS NOT NULL ";
		} else {
			$where_query_text = " WHERE a.completion_date IS NOT NULL AND " . implode( ' AND ', $where_query );
		}

		$query = "
			SELECT 
		       	UUID() as uuid,
			    t.test_name, 
			    t.test_id, 
			    a.completion_date,
		       	u.user_name,
			    CONCAT('[', GROUP_CONCAT(JSON_OBJECT(
			        'id', o.id, 'option_text', o.option_text, 'option_value', o.option_value
			    )), ']') as options,
			    CONCAT('[', GROUP_CONCAT(DISTINCT JSON_OBJECT(
			        'question', q.question_text, 'answer', a.selected_options
			    )), ']') as answers_map
			FROM $answers_table a
			JOIN $tests_table t ON t.test_id = a.test_id
			JOIN $questions_table q ON q.id = a.question_id
			JOIN $options_table o ON o.question_id = q.id
			JOIN $users_table u ON u.id = a.user_id
			$where_query_text
			GROUP BY a.completion_date
			ORDER BY a.completion_date DESC
		";

		$result = $this->wpdb->get_results(
			count($where_prepared) > 0 ?
				$this->wpdb->prepare(
					$query,
					...$where_prepared
				) : $query);

		return isset( $result ) ? array_map( function ( $answer_data_item ) {

			return new AdminAnswerDataModel( $answer_data_item );
		}, $result ) : [];
	}
}