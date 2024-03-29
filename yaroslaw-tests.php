<?php
    /**
     * @package Yaroslaw tests package
     */
    /*
    Plugin Name: Yaroslaw tests
    Description: Psychological tests aggregator plugin
    Version: 1.0.1.0-beta
    Author: Andy
    License: GPLv2
    Domain: Yaroslaw tests
    */

    if( !defined( 'ABSPATH' ) ) {
        die;
    };

    if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
        require_once dirname( __FILE__ ) . '/vendor/autoload.php';
    }

    use Testings\Base\Activate;
    use Testings\Base\Deactivate;

    function activate_yaroslaw_tests_plugin() {
        Activate::activate();
    }

    function deactivate_yaroslaw_tests() {
        Deactivate::deactivate();
    }

    register_activation_hook( __FILE__, 'activate_yaroslaw_tests_plugin' );
    register_deactivation_hook( __FILE__, 'deactivate_yaroslaw_tests' );

    if( class_exists( 'Testings\\Init' ) ) {
        Testings\Init::register_services();
    }