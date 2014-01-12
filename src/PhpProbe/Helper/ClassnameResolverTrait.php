<?php

namespace PhpProbe\Helper;

/**
 * Trait ClassnameResolverTrait
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Helper
 */
trait ClassnameResolverTrait
{
    /**
     * Get an Adapter class name from it's type
     *
     * @param string $type Probe type
     *
     * @throws \RuntimeException
     * @return string
     */
    public static function getClassNameFromType($type)
    {
        $rootNamespace = substr(__NAMESPACE__, 0, strpos(__NAMESPACE__, "\\"));
        $className     = "\\" . $rootNamespace . "\\" . static::OBJECT_TYPE;
        $className    .= "\\" . ucfirst($type) . static::OBJECT_TYPE;
        if (!class_exists($className)) {
            throw new \RuntimeException(sprintf("Class '%s' does not exist.", $className));
        }
        return $className;
    }
}
