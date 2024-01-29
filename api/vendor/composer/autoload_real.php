<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit224fe399fc532da7c4c93149ac1b0a6f
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit224fe399fc532da7c4c93149ac1b0a6f', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit224fe399fc532da7c4c93149ac1b0a6f', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit224fe399fc532da7c4c93149ac1b0a6f::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
