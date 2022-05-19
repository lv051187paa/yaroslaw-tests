<?php

namespace Testings\Services;

use Exception;
use Testings\Api\Database\TestAnswersRepository;
use Testings\DTO\AdminAnswerDataDTO;
use Testings\Models\AdminAnswerDataModel;

class AnswerDataFetchService {
	private TestAnswersRepository $answers_repository;
	public array $answers;

	public function __construct()
	{
		$this->answers_repository = new TestAnswersRepository();
	}

	/**
	 * @throws Exception
	 */
	public function getAnswersData( int|null $user_id, int|null $test_id ): array
	{

		$answers = $this->answers_repository->getAnswersByParams( $user_id, $test_id );
		$normalized_answers = [];

		foreach ( $answers as $answer ) {
			$answer->answers_map = $this->normalizeAnswersMap( $answer );
			$normalized_answers[] = new AdminAnswerDataDTO($answer);
		}

		return $normalized_answers;
	}

	private function normalizeAnswersMap( AdminAnswerDataModel $answer ): array
	{
		$normalized_answers_map = [];
		foreach ( $answer->answers_map as $answered_item ) {
			$answered_item->answer = $this->getOptionById($answer->options, $answered_item->answer );

			$normalized_answers_map[] = $answered_item;
		}

		return $normalized_answers_map;
	}

	private function getOptionById(array $option_data_list, string $answered_option_id_serialized): array
	{
		$answered_option_id_list = unserialize( $answered_option_id_serialized );
		$answered_option_data_list = [];
		foreach ( $answered_option_id_list as $option_id ) {
			$current_option = $this->findObjectById($option_id, $option_data_list);
			$answered_option_data_list[] = $current_option;
		}

		return $answered_option_data_list;
	}

	private function findObjectById(int $id, array $array){
		foreach ( $array as $element ) {
			if ( $id == $element->id ) {
				return $element;
			}
		}

		return false;
	}
}