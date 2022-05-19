<?php

namespace Testings\Api\Database;

use Exception;
use Testings\Models\UserModel;

class TestUsersRepository extends BaseDatabase {
	/**
	 * @throws Exception
	 */
	public function addNewUser( string $user_name, string $phone_number, string $email ): null|UserModel
	{
		$table_name = $this->table_names['TESTS_USERS'];

		$this->wpdb->query(
			$this->wpdb->prepare(
				"
	INSERT INTO $table_name
	( user_name, phone, email, is_active )
	VALUES ( %s, %s, %s, %d )
	",
				$user_name,
				$phone_number,
				$email,
				1
			)
		);

		if ( $this->wpdb->last_error ) {
			$is_duplicate = strpos( strtolower( $this->wpdb->last_error ), 'duplicate' );

			if ( $is_duplicate !== false ) {
				throw new Exception( 'Користувач з таким телефоном або поштою вже зареєстрований. Якщо це ви, то перевірте, чи вірно ви ввели свій телефон і адресу пошти', 500 );
			}

			throw new Exception( $this->wpdb->last_error, 500 );
		}

		$id             = $this->wpdb->insert_id;
		$submitted_user = $this->wpdb->get_row( "SELECT * FROM $table_name WHERE id = $id" );

		return new UserModel( $submitted_user );
	}

	public function editUser( int $user_id, string $user_name, string $phone_number, string $email ): UserModel
	{
		$table_name = $this->table_names['TESTS_USERS'];

		$this->wpdb->update(
			$table_name,
			[
				"user_name" => $user_name
			],
			[
				'id'    => $user_id,
				"phone" => $phone_number,
				"email" => $email
			],
			[ '%s' ],
			[ '%d', '%s', '%s' ]
		);

		$updated_user = $this->wpdb->get_row( "SELECT * FROM $table_name WHERE id = $user_id" );

		return new UserModel( $updated_user );
	}

	/**
	 * @throws Exception
	 */
	public function getUserByParams( $user_data ): UserModel|null
	{
		$table_name = $this->table_names['TESTS_USERS'];

		$user_id      = $user_data["user_id"];
		$phone_number = $user_data["phone_number"];
		$email        = $user_data["email"];

		$where_query = array();

		if ( ! empty( $user_id ) ) {
			$where_query[] = "id='" . $user_id . "'";
		}

		if ( ! empty( $phone_number ) && ! empty( $email ) ) {
			$where_query[] = "phone='" . $phone_number . "'";
			$where_query[] = "email='" . $email . "'";
		}

		if ( empty( $where_query ) ) {

			throw new Exception( "Incorrect user data", 400 );
		}

		$where_query_text = " WHERE " . implode( ' AND ', $where_query );
		$query            = "SELECT * FROM $table_name $where_query_text";

		$result = $this->wpdb->get_row(
			$this->wpdb->prepare(
				$query,
				$user_id,
				$phone_number
			)
		);

		if ( $this->wpdb->last_error ) {

			throw new Exception( $this->wpdb->last_error, 500 );
		}

		return isset( $result ) ? new UserModel( $result ) : null;
	}

	public function getAllUsers()
	{
		$table_name = $this->table_names['TESTS_USERS'];
		$query      = "SELECT * FROM $table_name";

		$result = $this->wpdb->get_results( $query );

		if ( $this->wpdb->last_error ) {

			throw new Exception( $this->wpdb->last_error, 500 );
		}

		$get_user_model = function ( $user_data ) {
			return new UserModel( $user_data );
		};

		return isset( $result ) ? array_map( $get_user_model, $result ) : null;
	}
}