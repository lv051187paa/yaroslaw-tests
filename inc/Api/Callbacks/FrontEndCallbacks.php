<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Api\Callbacks;

use Testings\Base\BaseController;
use Testings\Services\UserInteractionService;

// Callbacks for settings pages views (use templates folder for markups)

class FrontEndCallbacks extends BaseController {
	private UserInteractionService $uiService;

	public function __construct()
	{
		parent::__construct();
		$this->uiService = new UserInteractionService();
	}

	public function frontPage( $atts )
	{
		$test_id = (int) $atts['id'];
		$user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
		$ui_data = $this->uiService->getUserTestData( $user_id, $test_id );

		return $this->get_plugin_template( 'front_page', $ui_data );
	}
}