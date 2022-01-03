<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api\Database;

class CreateTables {
	private static function getQueryList()
	{
		return array(
			'yaroslaw_tests_list'              => "(
                 test_id INTEGER NOT NULL AUTO_INCREMENT,
                 test_name TEXT NOT NULL,
                 test_description TEXT NOT NULL,
                 is_active INTEGER,
                 archived INTEGER DEFAULT 0,
                 PRIMARY KEY (test_id)
             )",
			'yaroslaw_tests_questions'         => "(
	            id INTEGER NOT NULL AUTO_INCREMENT,
	            question_text TEXT NOT NULL,
	            question_type INTEGER NOT NULL,
	            is_active INTEGER,
	            test_id INTEGER NOT NULL,
	            archived INTEGER DEFAULT 0,
	            PRIMARY KEY (id)
	        )",
			'yaroslaw_tests_questions_options' => "(
	            id INTEGER NOT NULL AUTO_INCREMENT,
	            option_text TEXT NOT NULL,
	            option_value INTEGER NOT NULL,
	            question_id INTEGER NOT NULL,
	            archived INTEGER DEFAULT 0,
	            PRIMARY KEY (id)
	        )",
			'yaroslaw_tests_question_types'    => "(
	            id INTEGER NOT NULL AUTO_INCREMENT,
	            type_name TEXT NOT NULL,
	            selection_type TEXT NOT NULL,
	            PRIMARY KEY (id)
	        )"
		);
	}

	public static function createDbTable()
	{
		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql             = "";
		$charset_collate = $wpdb->get_charset_collate();

		foreach ( self::getQueryList() as $table_title => $query ) {
			//* Create Db tables
			$table_name = $wpdb->prefix . $table_title;
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
				$sql .= "CREATE TABLE $table_name $query $charset_collate;";
			}
		}

		if ( $sql != "" ) {
			dbDelta( $sql );
		}

		self::insertInitData();
		self::runMigrations();
	}

	private static function initDbData()
	{
		// first key should have unique value to check if this value exists in the table
		return [
			"yaroslaw_tests_question_types" => [
				[
					"type_name"      => "'Одна відповідь'",
					"selection_type" => "'single'"
				],
				[
					"type_name"      => "'Декілька відповідей'",
					"selection_type" => "'multiple'"
				],
				[
					"type_name"      => "'Так/Ні'",
					"selection_type" => "'single'"
				]
			]
		];
	}

	private static function insertInitData()
	{
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "";
		foreach ( self::initDbData() as $table_title => $data_list ) {
			$table_name = $wpdb->prefix . $table_title;
			foreach ( $data_list as $fields ) {
				$check_key        = array_key_first( $fields );
				$row_check_query  = "SELECT EXISTS(SELECT * FROM $table_name WHERE `" . $check_key . "` = $fields[$check_key]) as `has_row`";
				$row_check_result = $wpdb->get_results( $row_check_query, "ARRAY_A" );
				$is_existing_row  = is_array( $row_check_result ) && $row_check_result[0]['has_row'];
				if ( ! $is_existing_row ) {
					$insert_keys   = implode( ', ', array_keys( $fields ) );
					$insert_values = implode( ', ', array_values( $fields ) );

					$sql .= "INSERT INTO $table_name ($insert_keys) VALUES ($insert_values);";
				}
			}
		}

		if ( $sql != "" ) {
			dbDelta( $sql );
		}
	}

	private static function runMigrations()
	{
		global $wpdb;

		// add archived column for all tables
		$tables = array_keys(self::getQueryList());
		foreach ($tables as $table_name) {
			$prefixed_table_name = $wpdb->prefix . $table_name;
			$is_column_exist = (bool) $wpdb->query( "SHOW COLUMNS FROM $prefixed_table_name LIKE 'archived'" );
			if(!$is_column_exist) {
				$sql = "ALTER TABLE `$prefixed_table_name` ADD COLUMN archived INTEGER DEFAULT 0;";
				$wpdb->query( $sql );
			}
		}

		// remove deleted column for all tables
		foreach ($tables as $table_name) {
			$prefixed_table_name = $wpdb->prefix . $table_name;
			$is_column_exist = (bool) $wpdb->query( "SHOW COLUMNS FROM $prefixed_table_name LIKE 'deleted'" );
			if($is_column_exist) {
				$sql = "ALTER TABLE `$prefixed_table_name` DROP COLUMN deleted;";
				$wpdb->query( $sql );
			}
		}
	}
}