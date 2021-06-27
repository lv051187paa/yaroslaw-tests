<?php
    /**
     * @package Yaroslaw tests package
     */
    /*
    Plugin Name: Yaroslaw tests
    Description: Psychological tests aggregator plugin
    Version: 1.0.0
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

    use Inc\Base\Activate;
    use Inc\Base\Deactivate;

    function activate_yaroslaw_tests_plugin() {
        Activate::activate();
    }

    function deactivate_yaroslaw_tests() {
        Deactivate::deactivate();
    }

    register_activation_hook( __FILE__, 'activate_yaroslaw_tests_plugin' );
    register_deactivation_hook( __FILE__, 'deactivate_yaroslaw_tests' );

    if( class_exists( 'Inc\\Init' ) ) {
        Inc\Init::register_services();
    }