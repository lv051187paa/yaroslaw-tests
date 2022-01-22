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
	}

	private static function addArchivedColumn( $baseDb )
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

	private static function removeDeleteColumn( $baseDb )
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

	private static function removeArchivedColumnForQuestionTypesTable( $baseDb )
	{
		$table_name      = $baseDb->table_names['TESTS_QUESTION_TYPES'];
		$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'archived'" );
		if ( $is_column_exist ) {
			$sql = "ALTER TABLE $table_name DROP COLUMN archived;";
			$baseDb->wpdb->query( $sql );
		}
	}

	private static function addQuestionDescriptionColumn( $baseDb )
	{
		// add question description column
		$table_name      = $baseDb->table_names['TESTS_QUESTIONS'];
		$is_column_exist = (bool) $baseDb->wpdb->query( "SHOW COLUMNS FROM $table_name LIKE 'question_description'" );
		if ( ! $is_column_exist ) {
			$sql = "ALTER TABLE `$table_name` ADD COLUMN question_description TEXT DEFAULT '';";
			$baseDb->wpdb->query( $sql );
		}
	}
}