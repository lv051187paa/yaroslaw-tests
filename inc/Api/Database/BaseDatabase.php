<?php


namespace Testings\Api\Database;


class BaseDatabase
{
    public $wpdb;

    public $table_names = array();

    function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;

        $this->table_names = array(
            "TESTS_TABLE" => $wpdb->prefix."yaroslaw_tests_list"
        );
    }
}