<?php

namespace Testings\Models;

class AdminAnswerDataModel {
	public string $uuid;

	public string $test_name;

	public string $user_name;

	public int $test_id;

	public string|null $completion_date;

	public mixed $options;

	public mixed $answers_map;

	public function __construct( $raw_admin_answer_data )
	{
		$this->uuid            = $raw_admin_answer_data->uuid;
		$this->test_name       = $raw_admin_answer_data->test_name;
		$this->user_name       = $raw_admin_answer_data->user_name;
		$this->test_id         = (int) $raw_admin_answer_data->test_id;
		$this->completion_date = $raw_admin_answer_data->completion_date;
		$this->options         = json_decode( $raw_admin_answer_data->options );
		$this->answers_map     = json_decode( $raw_admin_answer_data->answers_map );
	}
}