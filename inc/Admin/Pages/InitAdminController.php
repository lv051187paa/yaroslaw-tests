<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Admin\Pages;

use Testings\Api\Callbacks\AdminCallbacks;
use Testings\Api\Callbacks\FrontEndCallbacks;
use Testings\Api\SettingsApi;
use Testings\Base\BaseController;

/**
 *
 */
class InitAdminController extends BaseController {
	public SettingsApi $settings;

	public AdminCallbacks $callbacks;

	public FrontEndCallbacks $callbacks_mngr;

	public array $pages = array();

	public array $subpages = array();


	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks_mngr = new FrontEndCallbacks();

		$this->callbacks = new AdminCallbacks();

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
				'menu_slug'  => 'yaroslaw_tests',
				'icon_url'   => 'dashicons-clipboard',
				'callback'   => array( $this->callbacks, 'adminDashboard' ),
				'position'   => 110
			]
		];
	}

	public function setSubpages()
	{
		// Plugin subpages

		$this->subpages = [
			[
				'parent_slug' => 'yaroslaw_tests',
				'page_title'  => 'Керування тестами',
				'menu_title'  => 'Керування тестами',
				'capability'  => 'manage_options',
				'menu_slug'   => 'yaroslaw_tests_settings',
				'callback'    => array( $this->callbacks, 'testsSettings' ),
			],
			[
				'parent_slug' => 'yaroslaw_tests',
				'page_title'  => 'Списки питань',
				'menu_title'  => 'Списки питань',
				'capability'  => 'manage_options',
				'menu_slug'   => 'yaroslaw_tests_questions',
				'callback'    => array( $this->callbacks, 'testsQuestions' ),
			],
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
}