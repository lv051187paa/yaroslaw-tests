<?php

namespace Testings\DTO;

use JetBrains\PhpStorm\Pure;

class UserDTO {
	public int $userId;
	public string $userName;
	public string $userPhone;
	public string $userEmail;

	#[Pure] public function __construct( $user_data )
	{
		$this->userId    = (int) $user_data->id;
		$this->userName  = $user_data->user_name;
		$this->userEmail = $user_data->email;
		$this->userPhone = $user_data->phone;
	}
}