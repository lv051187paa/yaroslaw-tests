<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Base;

use \Testings\Base\BaseController;

class Enqueue extends BaseController {
    public function register() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
    }

    function enqueue() {
	    $current_screen = get_current_screen();

	    if ( !strpos($current_screen->base, 'yaroslaw_tests') ) {
		    return;
	    } else {
	        wp_enqueue_style( 'yaroslaw-tests-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons' );
	        wp_enqueue_style( 'yaroslaw-tests-bootstrap-styles', $this->plugin_url . 'assets/vendors/bootstrap/bootstrap.min.css', __FILE__ );
	        wp_enqueue_style( 'yaroslaw-tests-styles', $this->plugin_url . 'assets/styles.css', __FILE__ );
		    wp_enqueue_script( 'yaroslaw-tests-jquery', $this->plugin_url . 'assets/vendors/jquery.js', __FILE__, null, true );
		    wp_enqueue_script( 'yaroslaw-tests-bootstrap-scripts', $this->plugin_url . 'assets/vendors/bootstrap/bootstrap.bundle.min.js', __FILE__, null, true );
		    wp_enqueue_script( 'yaroslaw-tests-scripts', $this->plugin_url . 'assets/main.js', __FILE__, null, true );
	    }
    }
}