<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Base;

class BaseController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin;
    public $plugin_name;

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
        $this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/yaroslaw-tests.php';
        $this->plugin_name = basename( plugin_dir_path(  dirname( __FILE__ , 2 ) ) );
    }

    protected function get_plugin_template( $filename, array $args)
    {
        foreach ($args as $key => $value) {
            $$key = $value;
        }
        return require_once("$this->plugin_path/templates/$filename.php");
    }

	protected function get_current_admin_url(): string
	{
		return admin_url(sprintf(basename($_SERVER['REQUEST_URI'])));
	}
}
