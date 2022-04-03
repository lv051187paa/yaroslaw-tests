<?php

namespace Testings\Admin\Pages;

use Testings\Api\Database\TestAnswersRepository;

class TestAnswers {
	private TestAnswersRepository $answers_repository;

	private function editAnswerHandler( int $test_id, int $question_id, mixed $serialized_options, string $question_type, int $current_option_value )
	{
		if ( $question_type == 'radio' ) {
			$new_serialized_options = serialize( array( $current_option_value ) );
			$result                 = $this->answers_repository->editSingleAnswer( $test_id, $question_id, $new_serialized_options );
		} else {
			$option_id_list      = unserialize( $serialized_options );
			$item_index          = array_search( $current_option_value, $option_id_list );
			$item_index !== false ? array_splice( $option_id_list, $item_index, 1 ) : array_push( $option_id_list, $current_option_value );
			$result              = $this->answers_repository->editSingleAnswer( $test_id, $question_id, serialize( $option_id_list ) );
		}
		wp_send_json_success( $result, 200 );
	}

	public function register()
	{
		add_action( 'wp_ajax_add_answer', array( $this, 'setAnswerHandler' ) );
		add_action( 'wp_ajax_nopriv_add_answer', array( $this, 'setAnswerHandler' ) );

		$this->answers_repository = new TestAnswersRepository();
	}

	public function setAnswerHandler()
	{
		$test_id                = (int) $_POST['testId'];
		$question_id            = (int) $_POST['questionId'];
		$question_type          = $_POST['type'];
		$option_value           = (int) $_POST['optionValue'];
		$answer = $this->answers_repository->getSingleAnswer( $test_id, $question_id );

		if ( $answer ) {
			$serialized_answers = $answer->selected_options;
			$this->editAnswerHandler( $test_id, $question_id, $serialized_answers, $question_type, $option_value );

			return;
		}

		$serialized_options = serialize( array( $option_value ) );
		$result             = $this->answers_repository->addNewAnswer( $test_id, $question_id, $serialized_options );

		wp_send_json_success( $result, 201 );
	}
}