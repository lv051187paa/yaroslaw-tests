<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api\Database;

use Testings\Api\Database\BaseDatabase;

class CreateTables
{
    private static function getQueryList()
    {
        return array(
            'yaroslaw_tests_list' => "(
                 test_id INTEGER NOT NULL AUTO_INCREMENT,
                 test_name TEXT NOT NULL,
                 test_description TEXT NOT NULL,
                 is_active INTEGER,
                 deleted INTEGER,
                 PRIMARY KEY (test_id)
             )"
        );
    }

    public static function createTestsTable()
    {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "";
        $charset_collate = $wpdb->get_charset_collate();

        foreach (self::getQueryList() as $table_title => $query) {
            //* Create the tests table
            $table_name = $wpdb->prefix . 'yaroslaw_tests_list';
            if ($wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name) {
                $sql .= "CREATE TABLE $table_name $query $charset_collate;";
            }
        }

        if($sql != "") {
            dbDelta( $sql );
        }
    }
}