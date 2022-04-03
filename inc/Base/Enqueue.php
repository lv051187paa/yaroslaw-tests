<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Base;

class Enqueue extends BaseController {
    public function register() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
    }

    function enqueue_admin() {
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

	function enqueue() {
			wp_enqueue_style( 'yaroslaw-tests-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons' );
//			wp_enqueue_style( 'yaroslaw-tests-bootstrap-styles', $this->plugin_url . 'assets/vendors/bootstrap/bootstrap.min.css', __FILE__ );
			wp_enqueue_style( 'yaroslaw-tests-jquery.modal-styles', $this->plugin_url . 'assets/vendors/jquery.modal/jquery.modal.min.css', __FILE__ );
			wp_enqueue_style( 'yaroslaw-tests-jquery.toast-styles', $this->plugin_url . 'assets/vendors/jquery.toast/jquery.toast.min.css', __FILE__ );
			wp_enqueue_style( 'yaroslaw-tests-styles', $this->plugin_url . 'assets/frontend/styles.css', __FILE__ );
			wp_enqueue_script( 'yaroslaw-tests-jquery', $this->plugin_url . 'assets/vendors/jquery.js', __FILE__, null, true );
//			wp_enqueue_script( 'yaroslaw-tests-bootstrap-scripts', $this->plugin_url . 'assets/vendors/bootstrap/bootstrap.bundle.min.js', __FILE__, null, true );
			wp_enqueue_script( 'yaroslaw-tests-jquery.modal-scripts', $this->plugin_url . 'assets/vendors/jquery.modal/jquery.modal.min.js', __FILE__, null, true );
			wp_enqueue_script( 'yaroslaw-tests-jquery.toast-scripts', $this->plugin_url . 'assets/vendors/jquery.toast/jquery.toast.min.js', __FILE__, null, true );
			wp_enqueue_script( 'yaroslaw-tests-jquery.mask-scripts', $this->plugin_url . 'assets/vendors/jquery.inputmask.min.js', __FILE__, null, true );
			wp_enqueue_script( 'yaroslaw-tests-validation-scripts', $this->plugin_url . 'assets/vendors/jquery.validate.min.js', __FILE__, null, true );
			wp_enqueue_script( 'yaroslaw-tests-scripts', $this->plugin_url . 'assets/frontend/main.js', __FILE__, null, true );
	}
}