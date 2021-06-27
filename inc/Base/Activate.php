<?php
/**
 * @package Yaroslaw tests package
 */

 namespace Testings\Base;

 class Activate {
     public static function activate() {
        flush_rewrite_rules();
     }
 }