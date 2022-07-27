<?php

namespace Testings\Models;

use Exception;
use JetBrains\PhpStorm\Pure;

class TestQuestionGenerationModel {
	public string $question_text;

	public ?string $question_description;

	public string $question_type;

	public array $question_option_list;

	public ?string $name;

	private ?int $rate_min;

	private ?int $rate_max;

	private ?string $min_rate_description;

	private ?string $max_rate_description;

	/**
	 * @throws Exception
	 */
	public function __construct( $question_response )
	{
		if ( ! isset( $question_response->question_text ) ) {
			throw new Exception( "Помилка при створенні одного з питань. Відсутній текст запитання" . 400 );
		}

		if ( ! isset( $question_response->question_type ) ) {
			throw new Exception( "Помилка при створенні питання \"$question_response->question_text\". Не вказаний тип питання", 400 );
		}

		$this->question_text        = $question_response->question_text;
		$this->question_description = $question_response->question_description;
		$this->question_type        = $question_response->question_type;
		$this->name                 = $question_response->name;
		$this->rate_min             = $question_response->rate_min;
		$this->rate_max             = $question_response->rate_max;
		$this->min_rate_description = $question_response->min_rate_description;
		$this->max_rate_description = $question_response->max_rate_description;
		$this->question_option_list = $this->generateOptionList( $question_response->question_option_list );
	}

	private function generateOptionList( ?array $option_list ): array
	{
		if ( ! isset( $option_list ) ) {
			return $this->generateOptionsListFromParams();
		} else {
			return $this->generateOptionsListFromReponse( $option_list );
		}
	}

	#[Pure] private function generateOptionsListFromReponse( array $option_list ): array
	{
		$generated_option_list = [];
		foreach ( $option_list as $option ) {
			$option->option_text     = "'$option->option_text'";
			$generated_option_list[] = new OptionModel( $option );
		}

		return $generated_option_list;
	}

	/**
	 * @throws Exception
	 */
	private function generateOptionsListFromParams(): array
	{
		if (
			! isset( $this->min_rate_description ) ||
			! isset( $this->max_rate_description ) ||
			! isset( $this->rate_max ) ||
			! isset( $this->rate_min )
		) {
			throw new Exception( "Відсутні варіанти відповіді на питання", 400 );
		}

		$generated_option_list = [];

		for ( $i = $this->rate_min; $i <= $this->rate_max; $i ++ ) {
			$option           = [];
			$min_option_title = $i === (int) $this->rate_min ? $this->min_rate_description : "";
			$max_option_title = $i === (int) $this->rate_max ? $this->max_rate_description : "";
			$title            = $min_option_title ?: $max_option_title;

			$option['option_text']  = isset( $title ) ? "'$title $i'" : "'$i'";
			$option['option_value'] = $i;

			$generated_option_list[] = new OptionModel( (object) $option );
		}

		return $generated_option_list;
	}
}