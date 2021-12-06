<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Pages;

use \Testings\Api\SettingsApi;
use \Testings\Base\BaseController;
use \Testings\Api\Database\TestsRepository;
use \Testings\Api\Callbacks\AdminCallbacks;
use \Testings\Api\Callbacks\ManagerCallbacks;

/**
 *
 */
class Admin extends BaseController
{
    public $settings;

    public $callbacks;

    public $callbacks_mngr;

    public $pages = array();

    public $tests_repository;

    public function register()
    {
        add_action( 'admin_post_save_test', array($this, 'createTestItemHandler') );
        add_action( 'admin_post_remove_test', array($this, 'removeTestItemHandler') );
        add_action( 'wp_ajax_edit_test', array($this, 'editTestItemHandler') );

        $this->settings = new SettingsApi();

        $this->callbacks_mngr = new ManagerCallbacks();

        $this->callbacks = new AdminCallbacks();

        $this->tests_repository = new TestsRepository();

        $this->setPages();

        $this->setSubpages();

        $this->setSettings();

        $this->setSections();

        $this->setFields();

        $this->settings->addPages( $this->pages )
                       ->withSubpage( 'Відповіді' )
                       ->addSubPages( $this->subpages )
                       ->register();
    }

    public function setPages()
    {
        // Main plugin page
        $this->pages = [
            [
                'page_title' => 'Тести',
                'menu_title' => 'Тести',
                'capability' => 'manage_options',
                'menu_slug' => 'yaroslaw_tests',
                'icon_url' => 'dashicons-clipboard',
                'callback' => array($this->callbacks, 'adminDashboard'),
                'position' => 110
            ]
        ];
    }

    public function setSubpages()
    {
        // Plugin subpages

        $this->subpages = [
            [
                'parent_slug' => 'yaroslaw_tests',
                'page_title' => 'Керування тестами',
                'menu_title' => 'Керування тестами',
                'capability' => 'manage_options',
                'menu_slug' => 'yaroslaw_tests_settings',
                'callback' => array($this->callbacks, 'testsSettings'),
            ],
            [
                'parent_slug' => 'yaroslaw_tests',
                'page_title' => 'Списки питань',
                'menu_title' => 'Списки питань',
                'capability' => 'manage_options',
                'menu_slug' => 'yaroslaw_tests_questions',
                'callback' => array($this->callbacks, 'testsQuestions'),
            ]
        ];
    }

    public function setSettings()
    {
        $args = [

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

        $args = array();

        $this->settings->setFields( $args );
    }

	public function createTestItemHandler()
    {
        $success = $this->tests_repository->addNewTest();
        if($success) {
            status_header( 200 );
            //request handlers should exit() when they complete their task
            wp_redirect( $_SERVER["HTTP_REFERER"] );
        }

        // Add error handler

        exit(  );
    }

	public function removeTestItemHandler()
	{
		$test_id = $_POST['id'];

		$success = $this->tests_repository->removeSingleTest( $test_id );
		if($success) {
			status_header( 200 );
			//request handlers should exit() when they complete their task
			wp_redirect( $_SERVER["HTTP_REFERER"] );
		}

		// Add error handler

		exit(  );
	}

	function editTestItemHandler()
	{
		$test_id = $_POST['testId'];
		$test_name = $_POST['testName'];
		$test_description = $_POST['testDescription'];
		$is_test_active = $_POST['isTestActive'];

		$success = $this->tests_repository->editSingleTest( $test_id, $test_name, $test_description, $is_test_active );
		echo $success;
//		if($success) {
//			status_header( 200 );
//			//request handlers should exit() when they complete their task
//			wp_redirect( $_SERVER["HTTP_REFERER"] );
//		}

		// Add error handler

//		exit(  );
	}
}