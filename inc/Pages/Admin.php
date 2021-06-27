<?php
/**
 * @package Yaroslaw tests package
 */

namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\ManagerCallbacks;

/**
 *
 */
class Admin extends BaseController
{
    public $settings;

    public $callbacks;

    public $callbacks_mngr;

    public $pages = array();

    public function register()
    {
        $this->settings = new SettingsApi();

        $this->callbacks_mngr = new ManagerCallbacks();

        $this->callbacks = new AdminCallbacks();

        $this->setPages();

        $this->setSubpages();

        $this->setSettings();

        $this->setSections();

        $this->setFields();

        $this->settings->addPages( $this->pages )
                       ->withSubpage( 'Dashboard' )
                       ->addSubPages( $this->subpages )
                       ->register();
    }

    public function setPages()
    {
        // Main plugin page
        $this->pages = [

        ];
    }

    public function setSubpages()
    {
        // Plugin subpages

        $this->subpages = [

        ];
    }

    public function setSettings()
    {
        $args = [
            [
                'option_group' => 'my_test_plugin_settings',
                'option_name' => 'cpt_manager',
                'callback' => array($this->callbacks, 'checkboxSanitize')
            ],
            [
                'option_group' => 'my_test_plugin_settings',
                'option_name' => 'testimonial_manager',
                'callback' => array($this->callbacks, 'checkboxSanitize')
            ],
            [
                'option_group' => 'my_test_plugin_settings',
                'option_name' => 'templates_manager',
                'callback' => array($this->callbacks, 'checkboxSanitize')
            ],
            [
                'option_group' => 'my_test_plugin_settings',
                'option_name' => 'text_example',
                'callback' => array($this->callbacks, 'myTestPluginOptionsGroup')
            ]
        ];

        $this->settings->setSettings( $args );
    }

    public function setSections()
    {
        // Sections for plugin pages

        $args = [
        ];

        $this->settings->setSections( $args );
    }

    public function setFields()
    {
        // Plugin pages fields

        $args = array(

        );

        $this->settings->setFields( $args );
    }
}