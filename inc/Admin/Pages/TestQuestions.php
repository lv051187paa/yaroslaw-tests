<?php

namespace Testings\Admin\Pages;

use JetBrains\PhpStorm\NoReturn;
use Testings\Api\Database\QuestionsRepository;

class TestQuestions {
	private QuestionsRepository $questions_repository;

	public function register()
	{
		add_action( 'admin_post_save_question', array( $this, 'saveTestQuestionHandler' ) );
		add_action( 'wp_ajax_get_questions', array( $this, 'getSingleTestQuestionList' ) );
		add_action( 'wp_ajax_get_question_types', array( $this, 'getQuestionTypeList' ) );
		add_action( 'wp_ajax_edit_question', array( $this, 'editSingleQuestion' ) );
		add_action( 'wp_ajax_archive_question', array( $this, 'removeSingleQuestion' ) );

		$this->questions_repository = new QuestionsRepository();
	}

	public function getSingleTestQuestionList()
	{
		$test_id = $_POST['testId'];

		$result = $this->questions_repository->getTestQuestions( $test_id );
		foreach ( $result as &$row ) {
			foreach ( $row as $key => &$value ) {
				if ( $key == 'options' ) {
					$value = json_decode( $value );
				}
				if ( is_numeric( $value ) ) {
					$value = (int) $value;
				}
			}
		}
		wp_send_json_success( $result, 200 );
	}

	public function getQuestionTypeList()
	{
		$result = $this->questions_repository->getQuestionTypeList();

		wp_send_json_success( $result, 200 );
	}

	#[NoReturn] public function saveTestQuestionHandler()
	{
		$question_text        = $_POST['question_text'];
		$question_description = $_POST['question_description'];
		$question_group       = $_POST['question_group'] ?: (int)$_POST['question_count'] + 1;
		$question_type_id     = $_POST['question_type'];
		$test_id              = (int) $_POST['test_id'];

		$question = $this->questions_repository->addNewTestQuestion( $question_text, $question_description, $question_type_id, $test_id, $question_group );

		if ( isset( $question ) ) {
			status_header( 200 );
			//request handlers should exit() when they complete their task
			wp_redirect( $_SERVER["HTTP_REFERER"] );
		}

		// Add error handler

		exit();
	}

	public function editSingleQuestion()
	{
		$question_id          = $_POST['questionId'];
		$question_text        = $_POST['questionName'];
		$question_description = $_POST['questionDescription'];
		$question_group       = $_POST['questionGroup'];
		$question_type        = $_POST['questionTypeId'];
		$is_active            = $_POST['isQuestionActive'];
		$result               = $this->questions_repository->editSingleQuestion( $question_id, $question_text, $question_description, $question_type, $is_active, $question_group );

		wp_send_json_success( $result, 200 );
	}

	public function removeSingleQuestion()
	{
		$question_id = $_POST['questionId'];

		$result = $this->questions_repository->removeSingleQuestion( $question_id );

		wp_send_json_success( $result, 200 );
	}
}