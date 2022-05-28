<?php

namespace Testings\Api\Database;

use Exception;

class TestsRepository extends BaseDatabase {
	/**
	 * @throws Exception
	 */
	public function addNewTest( ?string $test_description, string $test_name )
	{
		$table_name = $this->table_names['TESTS_TABLE'];

		$this->wpdb->query(
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

		if ( $this->wpdb->last_error ) {

			$is_duplicate = strpos( strtolower( $this->wpdb->last_error ), 'duplicate' );

			if ( $is_duplicate !== false ) {
				throw new Exception( "Тест з назвою \"$test_name\" вже існує", 500 );
			}

			throw new Exception( "Помилка при створенні тесту", 500 );
		}

		$id       = $this->wpdb->insert_id;
		$new_test = $this->wpdb->get_row( "SELECT * FROM $table_name WHERE test_id = $id" );

		return $new_test;
	}

	public function getTestsList( ?int $user_id = null ): array
	{
		$test_table     = $this->table_names['TESTS_TABLE'];
		$answers_table  = $this->table_names['TESTS_ANSWERS'];
		$where_query    = [];
		$where_prepared = [];

		if ( isset( $user_id ) ) {
			$where_query[]    = "a.user_id = %d";
			$where_prepared[] = $user_id;
		}

		if ( empty( $where_query ) ) {
			$where_query_text = "WHERE `archived` = 0";
		} else {
			$where_query_text = " WHERE `archived` = 0 AND " . implode( ' AND ', $where_query );
		}

		$query = "
			SELECT 
			       t.*, 
			       COUNT(DISTINCT a.user_id) as test_users,
			       COUNT(DISTINCT a.completion_date) as test_completed_counter
			FROM $test_table t
			LEFT JOIN $answers_table a ON a.test_id = t.test_id
			$where_query_text
			GROUP BY t.test_id
		";


		return $this->wpdb->get_results( count( $where_prepared ) === 0 ? $query :
			$this->wpdb->prepare(
				$query,
				...$where_prepared
			) );
	}

	public function getTestDetails( int $test_id )
	{
		$tests_table_name = $this->table_names['TESTS_TABLE'];
		$query            = "SELECT 
					*
				    FROM $tests_table_name t 
				    WHERE t.test_id = $test_id AND t.archived = 0";

		return $this->wpdb->get_row( $query, 'ARRAY_A' );
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

	public function getTestUsersCount( int $test_id )
	{
		$table_name = $this->table_names['TESTS_TABLE'];

		$query = "
		SELECT 
			COUNT(DISTINCT user_id) as test_user
		FROM $table_name
		WHERE test_id = %d AND user_id != 0
		";

		$result = $this->wpdb->get_row(
			$this->wpdb->prepare(
				$query,
				$test_id
			)
		);

		return $result;
	}
}