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
        wp_enqueue_style( 'yaroslaw-tests-styles', $this->plugin_url . 'assets/styles.css', __FILE__ );
        wp_enqueue_script( 'yaroslaw-tests-scripts', $this->plugin_url . 'assets/main.js', __FILE__ );
    }
}