<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api\Callbacks;

use Testings\Base\BaseController;
use Testings\Api\Database\TestsRepository;
use Testings\Api\Database\QuestionsRepository;

// Callbacks for base admin pages views (use templates folder for markups)

class AdminCallbacks extends BaseController {
	private $tests_repository;

	public function __construct()
	{
		parent::__construct();
		$this->tests_repository     = new TestsRepository();
		$this->questions_repository = new QuestionsRepository();
	}

	public function setClassNamePrefix( string $class_name )
	{
		return "$this->plugin_name-$class_name";
	}

	public function adminDashboard()
	{
		return require_once "$this->plugin_path/templates/admin.php";
	}

	public function testsSettings()
	{
		$tests = $this->tests_repository->getTestsList();

		return $this->get_plugin_template( 'tests_settings', array(
			'tests' => $tests,
		) );
	}

	public function testsQuestions()
	{
		$selected_test_id   = array_key_exists( 'testId', $_GET ) ? $_GET['testId'] : null;
		$tests              = $this->tests_repository->getTestsList();
		$question_types     = $this->questions_repository->getQuestionTypeList();
		$current_page       = $this->get_current_admin_url();
		$test_question_list = [];
		$current_test_index = array_search($selected_test_id, array_column($tests, 'test_id'));
		$selected_test_name = "";
		if(is_numeric($current_test_index)) {
			$selected_test_name = $tests[$current_test_index]['test_name'];
		}
		if ( isset( $selected_test_id ) ) {
			$test_question_list = $this->questions_repository->getTestQuestions( $selected_test_id );
		}

		return $this->get_plugin_template( 'tests_questions', array(
			'tests'            => $tests,
			'question_types'   => $question_types,
			'current_page_url' => $current_page,
			'question_list'    => $test_question_list,
			'current_test_id'  => $selected_test_id,
			'current_test_name'  => $selected_test_name,
		) );
	}
}