<?php

namespace Testings\Services;

use JetBrains\PhpStorm\ArrayShape;
use Testings\Api\Database\QuestionsRepository;
use Testings\Api\Database\TestAnswersRepository;
use Testings\Api\Database\TestsRepository;
use Testings\Helpers\Normalizers;

class UserInteractionService {
	private TestsRepository $tests_repository;
	private QuestionsRepository $questions_repository;
	private TestAnswersRepository $answers_repository;

	public function __construct()
	{
		$this->tests_repository     = new TestsRepository();
		$this->questions_repository = new QuestionsRepository();
		$this->answers_repository   = new TestAnswersRepository();
	}

	#[ArrayShape( [ 'test' => "mixed", 'questions' => "array", 'answers' => "array" ] )] public function getUserTestData( int $test_id ): array
	{
		$test                     = $this->tests_repository->getTestDetails( $test_id );
		$test_question_list       = $this->questions_repository->getTestQuestions( $test_id );
		$test_answers             = $this->answers_repository->getAnswerListByTest( $test_id ); // add filter by user later
		$normalized_question_list = Normalizers::queryListNormalizer( $test_question_list );
		$normalized_answer_list   = $this->formatAnswerList( $test_answers );

		return array(
			'test'      => $test['is_active'] == 1 ? $test : null,
			'questions' => array_filter( $normalized_question_list, array( $this, 'getActiveItems' ) ),
			'answers'   => $normalized_answer_list
		);
	}

	private function getActiveItems( array $item ): bool
	{
		return $item['is_active'] == 1 && ! empty( $item['options'] );
	}

	private function formatAnswerList( array $answers ): array
	{
		$formatted_answer_list = array();
		foreach ($answers as $answer_item) {
			$question_id = $answer_item['question_id'];
			$answers = unserialize($answer_item['selected_options']);
			$formatted_answer_list[$question_id] = $answers;
		}

		return $formatted_answer_list;
	}
}