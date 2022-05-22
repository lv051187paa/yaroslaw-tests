<?php

namespace Testings\DTO;

class AdminAnswerDataDTO {
	public string $uuid;

	public string $test_name;

	public string $user_name;

	public int $test_id;

	public string|null $completion_date;

	public array $answers_map;

	public function __construct( $raw_admin_answer_data )
	{
		$this->uuid            = $raw_admin_answer_data->uuid;
		$this->test_name       = $raw_admin_answer_data->test_name;
		$this->user_name       = $raw_admin_answer_data->user_name;
		$this->test_id         = $raw_admin_answer_data->test_id;
		$this->completion_date = $raw_admin_answer_data->completion_date;
		$this->answers_map     = $raw_admin_answer_data->answers_map;
	}
}