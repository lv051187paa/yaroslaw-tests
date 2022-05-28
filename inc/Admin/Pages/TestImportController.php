<?php

namespace Testings\Admin\Pages;

use Exception;
use Testings\Api\Database\OptionsRepository;
use Testings\Api\Database\QuestionsRepository;
use Testings\Api\Database\TestsRepository;
use Testings\Models\OptionModel;
use Testings\Models\TestGenerationModel;
use Testings\Models\TestQuestionGenerationModel;

class TestImportController {
	private TestsRepository $tests_repository;

	private QuestionsRepository $questions_repository;

	private OptionsRepository $options_repository;

	public function register()
	{
		add_action( 'wp_ajax_generate_test', array( $this, 'generateTestHandler' ) );

		$this->tests_repository     = new TestsRepository();
		$this->questions_repository = new QuestionsRepository();
		$this->options_repository   = new OptionsRepository();
	}

	public function generateTestHandler()
	{

		try {
			$generated_test = new TestGenerationModel( json_encode( $_POST['test_struture'] ) );
			$test = $this->tests_repository->addNewTest( $generated_test->test_description, $generated_test->test_name );
			if ( isset( $test ) && $test->test_id ) {

				/* @var $test_question TestQuestionGenerationModel */
				foreach ( $generated_test->question_list as $test_question ) {
					$question_type = $this->questions_repository->getQuestionTypeIdByValue( $test_question->question_type );
					$question      = $this->questions_repository->addNewTestQuestion( $test_question->question_text, $test_question->question_description, (int) $question_type->id, $test->test_id );

					if ( isset( $question ) && $question->id ) {
						$this->options_repository->addBulkOptions( $test_question->question_option_list, (int) $question->id );
					}
				}

				wp_send_json_success( $test, 200 );
			}

			wp_send_json_error( "Сталась непередбачувана помилка", 500 );
		} catch ( Exception $exception ) {
			wp_send_json_error( $exception->getMessage(), $exception->getCode() );
		}
	}
}