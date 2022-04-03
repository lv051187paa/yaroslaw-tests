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
            "TESTS_TABLE" => $wpdb->prefix."yaroslaw_tests_list",
	        "TESTS_QUESTIONS" => $wpdb->prefix."yaroslaw_tests_questions",
	        "TESTS_OPTIONS" => $wpdb->prefix."yaroslaw_tests_questions_options",
	        "TESTS_QUESTION_TYPES" => $wpdb->prefix."yaroslaw_tests_question_types",
	        "TESTS_ANSWERS" => $wpdb->prefix."yaroslaw_tests_tests_answers",
	        "TESTS_USERS" => $wpdb->prefix."yaroslaw_tests_users",
        );
    }
}