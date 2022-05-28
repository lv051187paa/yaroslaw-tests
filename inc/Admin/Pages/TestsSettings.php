<?php

namespace Testings\Admin\Pages;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use Testings\Api\Database\TestsRepository;
use WP_Error;

class TestsSettings {
	private TestsRepository $tests_repository;

	public function register()
	{
		add_action( 'admin_post_save_test', array( $this, 'createTestItemHandler' ) );
		add_action( 'wp_ajax_archive_test', array( $this, 'removeTestItemHandler' ) );
		add_action( 'wp_ajax_edit_test', array( $this, 'editTestItemHandler' ) );

		$this->tests_repository = new TestsRepository();
		$this->errors = new WP_Error;
	}

	#[NoReturn] public function createTestItemHandler(): void
	{
		$test_description = $_POST['test_description'];
		$test_name        = $_POST['test_name'];

		try {
			$success = $this->tests_repository->addNewTest( $test_description, $test_name );
			if ( $success ) {
				delete_transient( 'tests_settings' );
				status_header( 201 );
				//request handlers should exit() when they complete their task
				wp_redirect( wp_get_referer() );
			}

		} catch (Exception $exception) {
			status_header($exception->getCode());
			$errors = [
				'test_name' => $exception->getMessage()
			];

			set_transient( 'tests_settings', $errors, 1 );

			wp_redirect( $_SERVER["HTTP_REFERER"] );
		} finally {
			exit();
		}
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