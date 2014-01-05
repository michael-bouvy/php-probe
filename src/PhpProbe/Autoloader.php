<?php

namespace PhpProbe;

/**
 * Class Autoloader
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe
 */
class Autoloader
{
    /**
     * Register autoloader
     *
     * @param bool $prepend Prepend
     *
     * @return void
     */
    public static function register($prepend = false)
    {
        spl_autoload_register(__NAMESPACE__ . '\Autoloader::autoload', true, $prepend);
    }

    /**
     * Callback function to be registered as autoloader
     *
     * @param string $className Class name to be autoloaded
     */
    public static function autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require __DIR__ . '/../' . $fileName;
    }
}
