<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit48f757385b2555f2d440a368df7e1b6c
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Testings\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Testings\\' => 
        array (
            0 => __DIR__ . '/../..' . '/inc',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit48f757385b2555f2d440a368df7e1b6c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit48f757385b2555f2d440a368df7e1b6c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit48f757385b2555f2d440a368df7e1b6c::$classMap;

        }, null, ClassLoader::class);
    }
}
