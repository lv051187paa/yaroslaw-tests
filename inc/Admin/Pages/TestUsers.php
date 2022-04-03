<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Admin\Pages;

use Exception;
use Testings\Api\Database\TestUsersRepository;
use Testings\DTO\UserDTO;
use Testings\Services\PhoneNormalizeServise;

class TestUsers {
	private TestUsersRepository $test_users_repository;

	public function register(): void
	{
		$this->test_users_repository = new TestUsersRepository();

		add_action( 'wp_ajax_submit_user', array( $this, 'submitUserHandler' ) );
		add_action( 'wp_ajax_nopriv_submit_user', array( $this, 'submitUserHandler' ) );
		add_action( 'wp_ajax_get_user', array( $this, 'getUserInfo' ) );
		add_action( 'wp_ajax_nopriv_get_user', array( $this, 'getUserInfo' ) );
	}

	public function submitUserHandler(): void
	{

		$user_name    = $_POST['userName'];
		$phone_number = PhoneNormalizeServise::normalize( $_POST['userPhone'] );
		$email        = $_POST['userEmail'];

		$user_data = [
			'phone_number' => $phone_number,
			'email'        => $email
		];

		try {
			$user = $this->test_users_repository->getUserByParams( $user_data );

			if ( isset( $user ) ) {
				$result              = $this->test_users_repository->editUser( (int) $user->id, $user_name, $phone_number, $email );
				$_SESSION['user_id'] = $result->id;

				wp_send_json_success( new UserDTO( $result ), 200 );

				return;
			}

			try {
				$result              = $this->test_users_repository->addNewUser( $user_name, $phone_number, $email );
				$_SESSION['user_id'] = $result->id;

				wp_send_json_success( new UserDTO( $result ), 201 );
			} catch ( Exception $e ) {

				wp_send_json_error( $e->getMessage(), $e->getCode() );
			}
		} catch ( Exception $e ) {

			wp_send_json_error( $e->getMessage(), $e->getCode() );
		}
	}

	public function getUserInfo(): void
	{
		$user_id      = (int) $_POST['userId'];
		$phone_number = PhoneNormalizeServise::normalize( $_POST['userPhone'] );
		$user_data    = [
			'user_id'      => $user_id,
			'phone_number' => $phone_number
		];

		try {
			$result = $this->test_users_repository->getUserByParams( $user_data );
			if ( ! $result ) {

				wp_send_json_success( null, 200 );
			}
			$_SESSION['user_id'] = $result->id;

			wp_send_json_success( new UserDTO( $result ), 200 );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage(), $e->getCode() );
		}
	}
}