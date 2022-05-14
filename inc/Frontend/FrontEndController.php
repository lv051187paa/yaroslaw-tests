<?php

namespace Testings\Frontend;

use Testings\Api\Callbacks\FrontEndCallbacks;
use Testings\Base\BaseController;

class FrontEndController extends BaseController {
	private FrontEndCallbacks $frontend_callbacks;

	public function register()
	{
		$this->frontend_callbacks = new FrontEndCallbacks();
		add_shortcode( 'tests', array( $this, 'frontend_page_generator' ) );
		add_action( 'init', array( $this, 'register_my_session' ) );
	}

	public function register_my_session()
	{
		if ( ! session_id() ) {
			session_start();
		}
	}

	public function frontend_page_generator( $attrs ): bool|string
	{
		ob_start();
		$this->frontend_callbacks->frontPage( $attrs );

		return ob_get_clean();
	}
}