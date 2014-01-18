<?php

namespace PhpProbe\Helper;

use \PhpProbe\Helper\ClassnameResolverTrait;

/**
 * Class AdapterHelper
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Helper
 */
class AdapterHelper
{
    use ClassnameResolverTrait;

    const OBJECT_TYPE = 'Adapter';

    /**
     * Check if a PHP extension is loaded
     *
     * @param string $extension
     *
     * @throws \RuntimeException
     */
    public static function checkPhpExtension($extension)
    {
        if (!extension_loaded($extension)) {
            throw new \RuntimeException(sprintf('PHP %s extension is not installed', $extension));
        }
    }
}
