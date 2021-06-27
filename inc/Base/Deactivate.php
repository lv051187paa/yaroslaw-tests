<?php
/**
 * @package Yaroslaw tests package
 */

namespace Testings\Base;

 class Deactivate {
     public static function deactivate() {
        flush_rewrite_rules();
     }
 }