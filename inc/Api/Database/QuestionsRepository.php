<?php

namespace Testings\Api\Database;

use Testings\Api\Database\BaseDatabase;

class QuestionsRepository extends BaseDatabase {
	public function getQuestionTypeList()
	{

		$table_name = $this->table_names['TESTS_QUESTION_TYPES'];

		return $this->wpdb->get_results( "SELECT * FROM $table_name", 'ARRAY_A' );
	}

	public function getTestQuestions( int $test_id )
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

	public function addNewTestQuestion( string $question_text, string $question_description, int $question_type_id, int $test_id )
	{
		$table_name = $this->table_names['TESTS_QUESTIONS'];

		$result = $this->wpdb->query(
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

		return $this->wpdb->insert_id;
	}

	public function editSingleQuestion( int $question_id, string $question_text, string $question_description, int $question_type, int $is_active )
	{
		$table_name = $this->table_names['TESTS_QUESTIONS'];

		return $this->wpdb->update( $table_name, [
			'question_text'  => $question_text,
			'question_description'  => $question_description,
			'question_type' => $question_type,
			'is_active' => $is_active,
		], [ 'id' => (int) $question_id ], [ '%s', '%s', '%d', '%d' ], [ '%d' ] );
	}

	public function removeSingleQuestion( int $question_id )
	{
		$table_name = $this->table_names['TESTS_QUESTIONS'];

		return $this->wpdb->update( $table_name, [
			'archived'  => 1,
		], [ 'id' => (int) $question_id ], [ '%d' ], [ '%d' ] );
	}
}