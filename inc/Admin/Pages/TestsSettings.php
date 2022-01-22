<?php

namespace Testings\Admin\Pages;

use JetBrains\PhpStorm\NoReturn;
use Testings\Api\Database\TestsRepository;

class TestsSettings {
	public TestsRepository $tests_repository;

	public function register()
	{
		add_action( 'admin_post_save_test', array( $this, 'createTestItemHandler' ) );
		add_action( 'wp_ajax_archive_test', array( $this, 'removeTestItemHandler' ) );
		add_action( 'wp_ajax_edit_test', array( $this, 'editTestItemHandler' ) );

		$this->tests_repository = new TestsRepository();
	}

	#[NoReturn] public function createTestItemHandler()
	{
		$test_description = $_POST['test_description'];
		$test_name        = $_POST['test_name'];

		$success = $this->tests_repository->addNewTest( $test_description, $test_name );
		if ( $success ) {
			status_header( 200 );
			//request handlers should exit() when they complete their task
			wp_redirect( $_SERVER["HTTP_REFERER"] );
		}

		// Add error handler

		exit();
	}

	public function removeTestItemHandler()
	{
		$test_id = $_POST['id'];

		$result = $this->tests_repository->removeSingleTest( $test_id );
		wp_send_json_success( $result, 200 );
	}

	public function editTestItemHandler()
	{
		$test_id          = $_POST['testId'];
		$test_name        = $_POST['testName'];
		$test_description = $_POST['testDescription'];
		$is_test_active   = $_POST['isTestActive'];

		$result = $this->tests_repository->editSingleTest( $test_id, $test_name, $test_description, $is_test_active );
		wp_send_json_success( $result, 200 );
	}
}