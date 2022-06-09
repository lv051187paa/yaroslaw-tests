<?php

namespace Testings\Models;

use Exception;

class TestGenerationModel {
	public string $test_name;

	public ?string $test_description;

	public array $question_list;

	/**
	 * @throws Exception
	 */
	public function __construct($test_response)
	{

		$test_response = json_decode($test_response);

		if(!isset($test_response->test_name)) {
			throw new Exception("Помилка при створенні тесту. Не вказана назва тесту", 400);
		}

		if(!isset($test_response->question_list)) {
			throw new Exception("Помилка при створенні тесту \"$test_response->test_name\". не вказаний список запитань", 400);
		}

		$this->test_name = $test_response->test_name;
		$this->test_description = $test_response->test_description;
		$this->question_list = $this->generateQuestionList($test_response->question_list);
	}

	private function generateQuestionList(array $test_question_list): array
	{
		$generated_question_list = [];

		foreach ($test_question_list as $test_question) {
			$generated_question_list[] = new TestQuestionGenerationModel($test_question);
		}

		return $generated_question_list;
	}
}