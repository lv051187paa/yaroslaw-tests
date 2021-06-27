<?php
/**
 * @package Yaroslaw tests package
 */
namespace Inc\Base;

use \Inc\Base\BaseController;

// Links for plugin main WP settings page

class SettingsLinks extends BaseController {

    public function register() {
        add_filter( 'plugin_action_links_' . $this->plugin, array( $this, 'settings_link' ) );
    }

    function settings_link( $links ) {
        $settings_link = '<a href="admin.php?page=my_plugin_slug">Settings</a>';
        array_push( $links, $settings_link );

        return $links;
    }
}