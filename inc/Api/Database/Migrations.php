<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api\Database;

class Migrations {
	public static function runMigrations()
	{
		$baseDb = new BaseDatabase();

		self::addArchivedColumn( $baseDb );
		self::removeDeleteColumn( $baseDb );
		self::removeArchivedColumnForQuestionTypesTable( $baseDb );
		self::addQuestionDescriptionColumn( $baseDb );
		self::addCompleationDateForAnswersTable( $baseDb );
		self::addUserIdForAnswersTable( $baseDb );
		self::setUSerPhoneUniqueConstraint( $baseDb );
		self::setTestNameUniqueConstraint( $baseDb );
	}

	private static function addArchivedColumn( BaseDatabase $baseDb )
	{
		// add archived column for all tables
		$tables = array(
			$baseDb->table_names['TESTS_TABLE'],
			$baseDb->table_names['TESTS_QUESTIONS'],
			$baseDb->table_names['TESTS_OPTIONS'],
		);
		foreach ( $tables as $table_name ) {
			$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'archived'" );
			if ( ! $is_column_exist ) {
				$sql = "ALTER TABLE `$table_name` ADD COLUMN archived INTEGER DEFAULT 0;";
				$baseDb->wpdb->query( $sql );
			}
		}
	}

	private static function removeDeleteColumn( BaseDatabase $baseDb )
	{
		// remove deleted column for all tables
		$tables = array(
			$baseDb->table_names['TESTS_TABLE'],
			$baseDb->table_names['TESTS_QUESTIONS'],
			$baseDb->table_names['TESTS_OPTIONS'],
		);
		foreach ( $tables as $table_name ) {
			$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'deleted'" );
			if ( $is_column_exist ) {
				$sql = "ALTER TABLE `$table_name` DROP COLUMN deleted;";
				$baseDb->wpdb->query( $sql );
			}
		}
	}

	private static function removeArchivedColumnForQuestionTypesTable( BaseDatabase $baseDb )
	{
		$table_name      = $baseDb->table_names['TESTS_QUESTION_TYPES'];
		$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'archived'" );
		if ( $is_column_exist ) {
			$sql = "ALTER TABLE $table_name DROP COLUMN archived;";
			$baseDb->wpdb->query( $sql );
		}
	}

	private static function addQuestionDescriptionColumn( BaseDatabase $baseDb )
	{
		// add question description column
		$table_name      = $baseDb->table_names['TESTS_QUESTIONS'];
		$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'question_description'" );
		if ( ! $is_column_exist ) {
			$sql = "ALTER TABLE `$table_name` ADD COLUMN question_description TEXT DEFAULT '';";
			$baseDb->wpdb->query( $sql );
		}
	}

	private static function addCompleationDateForAnswersTable( BaseDatabase $baseDb )
	{
		// add test completion date column
		$table_name      = $baseDb->table_names['TESTS_ANSWERS'];
		$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'completion_date'" );
		if ( ! $is_column_exist ) {
			$sql = "ALTER TABLE `$table_name` ADD COLUMN completion_date TIMESTAMP NULL;";
			$baseDb->wpdb->query( $sql );
		}
	}

	private static function addUserIdForAnswersTable( BaseDatabase $baseDb )
	{
		// add user_id column
		$table_name      = $baseDb->table_names['TESTS_ANSWERS'];
		$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'user_id'" );
		if ( ! $is_column_exist ) {
			$sql = "ALTER TABLE `$table_name` ADD COLUMN user_id SMALLINT NOT NULL;";
			$baseDb->wpdb->query( $sql );
		}
	}

	private static function setUSerPhoneUniqueConstraint( BaseDatabase $baseDb )
	{
		// set user phone unique constraint
		$table_name      = $baseDb->table_names['TESTS_USERS'];
		$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'phone'" );
		if ( $is_column_exist ) {
			$is_column_unique = (bool) $baseDb->wpdb->query( "SHOW INDEXES FROM $table_name WHERE Key_name = 'phone'" );
			if ( !$is_column_unique ) {
				$sql = "ALTER TABLE `$table_name` ADD CONSTRAINT UNIQUE (phone);";
				$baseDb->wpdb->query( $sql );
			}
		}
	}

	private static function setTestNameUniqueConstraint( BaseDatabase $baseDb )
	{
		// set user phone unique constraint
		$table_name      = $baseDb->table_names['TESTS_TABLE'];
		$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'test_name'" );
		if ( $is_column_exist ) {
			$is_column_unique = (bool) $baseDb->wpdb->query( "SHOW INDEXES FROM $table_name WHERE Key_name = 'test_name'" );
			if ( !$is_column_unique ) {
				$sql = "ALTER TABLE `$table_name` ADD CONSTRAINT UNIQUE (test_name);";
				$baseDb->wpdb->query( $sql );
			}
		}
	}
}