<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Base;

use Testings\Api\Database\CreateTables;

class Activate
{
    public static function activate()
    {
        CreateTables::createTestsTable();
        flush_rewrite_rules();
    }


}