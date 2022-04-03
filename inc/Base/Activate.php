<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Base;

use Testings\Api\Database\CreateTables;
use Testings\Api\Database\Migrations;

class Activate
{
    public static function activate()
    {
        CreateTables::createDbTables();
	    Migrations::runMigrations();
        flush_rewrite_rules();
    }


}