<?php
/**
 * @package Yaroslaw tests package
 */
namespace Testings;

final class Init {

    public static function get_services() {
        return [
            Admin\Pages\InitAdminController::class,
            Admin\Pages\TestsSettings::class,
            Admin\Pages\TestQuestions::class,
            Admin\Pages\TestQuestionOptions::class,
            Admin\Pages\TestAnswers::class,
            Base\Enqueue::class,
            Base\SettingsLinks::class,
            Frontend\FrontEndController::class
        ];
    }

    public static function register_services() {
        foreach( self::get_services() as $class ) {
            $service = self::instantiate( $class );
            if( method_exists( $service, 'register' ) ) {
                $service->register();
            }
        }
    }

    private static function instantiate( $class ) {
        return new $class();
    }
}
