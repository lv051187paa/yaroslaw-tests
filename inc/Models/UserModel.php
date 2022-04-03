<?php

namespace Testings\Models;

class UserModel {
	public int $id;
	public string $user_name;
	public string $email;
	public string $phone;
	public int $is_active;

	public function __construct($raw_user_data)
	{
		$this->id = (int) $raw_user_data->id;
		$this->user_name = $raw_user_data->user_name;
		$this->phone = $raw_user_data->phone;
		$this->email = $raw_user_data->email;
		$this->is_active = (int) $raw_user_data->is_active;
	}
}