<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api\Callbacks;

use Testings\Base\BaseController;
use Testings\Api\Database\TestsRepository;

// Callbacks for base admin pages views (use templates folder for markups)

class AdminCallbacks extends BaseController
{
    private $tests_repository;

    public function __construct()
    {
        parent::__construct();
        $this->tests_repository = new TestsRepository();
    }

    public function setClassNamePrefix( string $class_name )
    {
        return "$this->plugin_name-$class_name";
    }

    public function adminDashboard()
    {
        return require_once "$this->plugin_path/templates/admin.php";
    }

    public function testsSettings()
    {
        $tests = $this->tests_repository->getTestsList();
        return $this->get_plugin_template( 'tests_settings', array(
            'tests' => $tests,
        ) );
    }

    public function testsQuestions()
    {
        return require_once "$this->plugin_path/templates/tests_questions.php";
    }
}