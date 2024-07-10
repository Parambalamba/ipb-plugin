<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0ea9d67ebe69e0cc31f3d61e3287c7f8
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0ea9d67ebe69e0cc31f3d61e3287c7f8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0ea9d67ebe69e0cc31f3d61e3287c7f8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0ea9d67ebe69e0cc31f3d61e3287c7f8::$classMap;

        }, null, ClassLoader::class);
    }
}