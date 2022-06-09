<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api\Database;

use Testings\Api\Database\BaseDatabase;

class CreateTables {
	private static function getQueryList( BaseDatabase $baseDb ): array
	{
		return array(
			$baseDb->table_names['TESTS_TABLE']          => "(
                 test_id INTEGER NOT NULL AUTO_INCREMENT,
                 test_name TEXT NOT NULL,
                 test_description TEXT NOT NULL,
                 is_active INTEGER,
                 archived INTEGER DEFAULT 0,
                 PRIMARY KEY (test_id)
             )",
			$baseDb->table_names['TESTS_QUESTIONS']      => "(
	            id INTEGER NOT NULL AUTO_INCREMENT,
	            question_text TEXT NOT NULL,
	            question_type INTEGER NOT NULL,
	            is_active INTEGER,
	            test_id INTEGER NOT NULL,
	            archived INTEGER DEFAULT 0,
	            PRIMARY KEY (id)
	        )",
			$baseDb->table_names['TESTS_OPTIONS']        => "(
	            id INTEGER NOT NULL AUTO_INCREMENT,
	            option_text TEXT NOT NULL,
	            option_value INTEGER NOT NULL,
	            question_id INTEGER NOT NULL,
	            archived INTEGER DEFAULT 0,
	            PRIMARY KEY (id)
	        )",
			$baseDb->table_names['TESTS_QUESTION_TYPES'] => "(
	            id INTEGER NOT NULL AUTO_INCREMENT,
	            type_name TEXT NOT NULL,
	            selection_type TEXT NOT NULL,
	            PRIMARY KEY (id)
	        )",
			$baseDb->table_names['TESTS_ANSWERS'] => "(
	            id INTEGER NOT NULL AUTO_INCREMENT,
	            test_id INTEGER NOT NULL,
	            question_id INTEGER NOT NULL,
	            selected_options LONGTEXT NOT NULL,
	            PRIMARY KEY (id)
	        )",
			$baseDb->table_names['TESTS_USERS'] => "(
	            id INTEGER NOT NULL AUTO_INCREMENT,
	            user_name TINYTEXT NOT NULL,
	            email TINYTEXT NOT NULL,
	            phone TINYTEXT NOT NULL,
	            is_active INTEGER DEFAULT 1,
	            PRIMARY KEY (id)
	        )"
		);
	}

	public static function createDbTables()
	{
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$baseDb = new BaseDatabase();

		$sql             = "";
		$charset_collate = $baseDb->wpdb->get_charset_collate();

		foreach ( self::getQueryList( $baseDb ) as $table_title => $query ) {
			//* Create Db tables
			if ( $baseDb->wpdb->get_var( "SHOW TABLES LIKE '$table_title'" ) != $table_title ) {
				$sql .= "CREATE TABLE $table_title $query $charset_collate;";
			}
		}

		if ( $sql != "" ) {
			dbDelta( $sql );
		}

		self::insertInitData( $baseDb );
	}

	private static function initDbData( BaseDatabase $baseDb )
	{
		// first key should have unique value to check if this value exists in the table
		return [
			$baseDb->table_names['TESTS_QUESTION_TYPES'] => [
				[
					"type_name"      => "'Одна відповідь'",
					"selection_type" => "'single'"
				],
				[
					"type_name"      => "'Декілька відповідей'",
					"selection_type" => "'multiple'"
				]
			]
		];
	}

	private static function insertInitData( BaseDatabase $baseDb )
	{
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "";
		foreach ( self::initDbData( $baseDb ) as $table_title => $data_list ) {
			foreach ( $data_list as $fields ) {
				$check_key        = array_key_first( $fields );
				$row_check_query  = "SELECT EXISTS(SELECT * FROM $table_title WHERE `" . $check_key . "` = $fields[$check_key]) as `has_row`";
				$row_check_result = $baseDb->wpdb->get_results( $row_check_query, "ARRAY_A" );
				$is_existing_row  = is_array( $row_check_result ) && $row_check_result[0]['has_row'];
				if ( ! $is_existing_row ) {
					$insert_keys   = implode( ', ', array_keys( $fields ) );
					$insert_values = implode( ', ', array_values( $fields ) );

					$sql .= "INSERT INTO $table_title ($insert_keys) VALUES ($insert_values);";
				}
			}
		}

		if ( $sql != "" ) {
			dbDelta( $sql );
		}
	}
}