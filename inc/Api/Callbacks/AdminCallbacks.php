<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api\Callbacks;

use Testings\Api\Database\TestUsersRepository;
use Testings\Base\BaseController;
use Testings\Api\Database\TestsRepository;
use Testings\Api\Database\QuestionsRepository;
use Testings\Services\AnswerDataFetchService;

// Callbacks for base admin pages views (use templates folder for markups)

class AdminCallbacks extends BaseController {
	private $tests_repository;
	private $questions_repository;

	public function __construct()
	{
		parent::__construct();
		$this->tests_repository     = new TestsRepository();
		$this->questions_repository = new QuestionsRepository();
		$this->users_repository     = new TestUsersRepository();
	}

	public function setClassNamePrefix( string $class_name )
	{
		return "$this->plugin_name-$class_name";
	}

	public function adminDashboard()
	{
		$selected_user_id   = array_key_exists( 'userId', $_GET ) ? (int) $_GET['userId'] : null;
		$selected_test_id   = array_key_exists( 'testId', $_GET ) && $_GET['testId'] !== "" ? (int) $_GET['testId'] : null;
		$users              = $this->users_repository->getAllUsers();
		$tests              = $this->tests_repository->getTestsList( $selected_user_id );
		$current_test_index = array_search( $selected_test_id, array_column( $tests, 'test_id' ) );
		$selected_test_name = is_numeric( $current_test_index ) ? $tests[ $current_test_index ]->test_name : "";

		$current_page       = $this->get_current_admin_url_no_query_params();
		$current_user_index = array_search( $selected_user_id, array_column( $users, 'id' ) );
		$current_user       = $current_user_index !== false ? $users[ $current_user_index ] : null;

		$answers_fetch_service = new AnswerDataFetchService();
		$answers               = $answers_fetch_service->getAnswersData( $selected_user_id, $selected_test_id );

		return $this->get_plugin_template( "admin", array(
			'tests'             => $tests,
			'users'             => $users,
			'current_page_url'  => $current_page,
			'current_test_id'   => $selected_test_id,
			'current_test_name' => $selected_test_name,
			'current_user'      => $current_user,
			'answer_list'       => $answers
		) );
	}

	public function testsSettings()
	{
		$tests  = $this->tests_repository->getTestsList();
		$errors = get_transient( 'tests_settings' );

		return $this->get_plugin_template( 'tests_settings', array(
			'tests'  => $tests,
			'errors' => $errors
		) );
	}

	public function testsQuestions()
	{
		$selected_test_id   = array_key_exists( 'testId', $_GET ) ? $_GET['testId'] : null;
		$tests              = $this->tests_repository->getTestsList();
		$question_types     = $this->questions_repository->getQuestionTypeList();
		$current_page       = $this->get_current_admin_url_no_query_params();
		$test_question_list = isset( $selected_test_id ) ? $this->questions_repository->getTestQuestions( $selected_test_id ) : [];
		$current_test_index = array_search( $selected_test_id, array_column( $tests, 'test_id' ) );
		$selected_test_name = is_numeric( $current_test_index ) ? $tests[ $current_test_index ]->test_name : "";

		return $this->get_plugin_template( 'tests_questions', array(
			'tests'             => $tests,
			'question_types'    => $question_types,
			'current_page_url'  => $current_page,
			'question_list'     => $test_question_list,
			'current_test_id'   => $selected_test_id,
			'current_test_name' => $selected_test_name,
		) );
	}

	public function testsGeneration()
	{
		return $this->get_plugin_template( 'tests_import' );
	}
}