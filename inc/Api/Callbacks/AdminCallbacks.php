<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api\Callbacks;

use Testings\Base\BaseController;

// Callbacks for base admin pages views (use templates folder for markups)

class AdminCallbacks extends BaseController
{
    public function adminDashboard()
    {
        return require_once "$this->plugin_path/templates/admin.php";
    }

    public function testsSettings()
    {
        return require_once "$this->plugin_path/templates/tests_settings.php";
    }

    public function testsQuestions()
    {
        return require_once "$this->plugin_path/templates/tests_questions.php";
    }
}