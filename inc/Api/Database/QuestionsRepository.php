<?php

namespace Testings\Api\Database;

use Exception;

class QuestionsRepository extends BaseDatabase {
	public function getQuestionTypeList()
	{

		$table_name = $this->table_names['TESTS_QUESTION_TYPES'];

		return $this->wpdb->get_results( "SELECT * FROM $table_name", 'ARRAY_A' );
	}

	/**
	 * @throws Exception
	 */
	public function getQuestionTypeIdByValue( string $value ): array|object
	{
		$table_name = $this->table_names['TESTS_QUESTION_TYPES'];
		$query      = "SELECT * FROM $table_name WHERE selection_type = %s";

		$result = $this->wpdb->get_row( $this->wpdb->prepare( $query, $value ) );

		if ( ! isset( $result ) ) {
			throw new Exception( "Невірно вказаний тип одного з питань. Тип може бути в двох варіантах \"single\" або \"multiple\"", 400 );
		}

		if ( $this->wpdb->last_error ) {

			throw new Exception( "Помилка в типі одного з питань", 500 );
		}

		return $result;
	}

	public function getTestQuestions( int $test_id ): object|array|null
	{
		$table_name                = $this->table_names['TESTS_QUESTIONS'];
		$question_types_table_name = $this->table_names['TESTS_QUESTION_TYPES'];
		$options_table_name        = $this->table_names['TESTS_OPTIONS'];
		$query                     = "SELECT 
										q.id, 
									    q.question_text, 
									    q.question_type, 
									    q.is_active, 
									    q.test_id, 
                                        q.archived,
       									q.question_description,
									    t.type_name, 
									    t.selection_type, 
									    IF(COUNT(o.id) = 0, JSON_ARRAY(), CONCAT('[', GROUP_CONCAT(
										    JSON_OBJECT(
										        'id', o.id, 'text', o.option_text, 'isArchived', o.archived, 'value', o.option_value
										    )
										), ']')) AS options
									FROM $table_name q
									JOIN $question_types_table_name t ON question_type = t.id 
									LEFT JOIN $options_table_name o ON q.id = o.question_id AND o.archived = 0
									WHERE `test_id` = %d AND q.archived = %d
									GROUP BY q.id";
		$request                   = $this->wpdb->prepare( $query, $test_id, 0 );

		return $this->wpdb->get_results( $request, 'ARRAY_A' );
	}

	public function addNewTestQuestion( string $question_text, ?string $question_description, int $question_type_id, int $test_id )
	{
		$table_name = $this->table_names['TESTS_QUESTIONS'];

		$this->wpdb->query(
			$this->wpdb->prepare(
				"
		INSERT INTO $table_name
		( question_text, question_description, question_type, is_active, test_id )
		VALUES ( %s, %s, %d, %d, %d )
		",
				$question_text,
				$question_description,
				$question_type_id,
				1,
				$test_id
			)
		);

		if ( $this->wpdb->last_error ) {

			throw new Exception( "Помилка при створенні одного з питань", 500 );
		}

		$id          = $this->wpdb->insert_id;
		$new_quesion = $this->wpdb->get_row( "SELECT * FROM $table_name WHERE id = $id" );

		return $new_quesion;

	}

	public function editSingleQuestion( int $question_id, string $question_text, string $question_description, int $question_type, int $is_active )
	{
		$table_name = $this->table_names['TESTS_QUESTIONS'];

		return $this->wpdb->update( $table_name, [
			'question_text'        => $question_text,
			'question_description' => $question_description,
			'question_type'        => $question_type,
			'is_active'            => $is_active,
		], [ 'id' => (int) $question_id ], [ '%s', '%s', '%d', '%d' ], [ '%d' ] );
	}

	public function removeSingleQuestion( int $question_id )
	{
		$table_name = $this->table_names['TESTS_QUESTIONS'];

		return $this->wpdb->update( $table_name, [
			'archived' => 1,
		], [ 'id' => (int) $question_id ], [ '%d' ], [ '%d' ] );
	}
}