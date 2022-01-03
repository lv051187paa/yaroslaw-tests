<?php

namespace Testings\Pages;

use \Testings\Api\Database\OptionsRepository;

class TestQuestionOptions {
	public $options_repository;

	public function register()
	{
		add_action( 'wp_ajax_option_details', array( $this, 'getSingleOptionHandler' ) );
		add_action( 'wp_ajax_save_option', array( $this, 'saveTestOptionHandler' ) );
		add_action( 'wp_ajax_remove_option', array( $this, 'removeOptionHandler' ) );
		add_action( 'wp_ajax_edit_option', array( $this, 'editSingleOptionHandler' ) );
		add_action( 'add_bulk_options', array( $this, 'addBulkOptionsHandler' ), 10, 2 );

		$this->options_repository = new OptionsRepository();
	}

	public function getSingleOptionHandler()
	{
		$option_id = $_POST['optionId'];
		$result    = $this->options_repository->getSingleOption( $option_id );
		foreach ( $result as &$row ) {
			foreach ( $row as $key => &$value ) {
				if ( is_numeric( $value ) ) {
					$value = (int) $value;
				}
			}
		}

		wp_send_json_success( $result[0], 200 );
	}

	public function saveTestOptionHandler()
	{
		$option_text  = $_POST['optionText'];
		$option_value = $_POST['optionValue'];
		$question_id  = $_POST['questionId'];

		$result = $this->options_repository->addNewTestOption( $option_text, $option_value, $question_id );
		wp_send_json_success( $result, 200 );

		status_header( 200 );
		//request handlers should exit() when they complete their task
		wp_redirect( $_SERVER["HTTP_REFERER"] );

	}

	public function removeOptionHandler()
	{
		$option_id = $_POST['optionId'];
		$result    = $this->options_repository->removeSingleOption( $option_id );

		wp_send_json_success( $result, 200 );
	}

	public function editSingleOptionHandler()
	{
		$option_id    = $_POST['optionId'];
		$option_value = $_POST['optionValue'];
		$option_text  = $_POST['optionText'];
		$result       = $this->options_repository->editSingleOption( $option_id, $option_text, $option_value );

		wp_send_json_success( $result, 200 );

	}

	public function addBulkOptionsHandler( array $options_list, int $question_id )
	{
		$this->options_repository->addBulkOptions($options_list, $question_id);
	}
}