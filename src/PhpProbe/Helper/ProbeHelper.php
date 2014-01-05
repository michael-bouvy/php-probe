<?php

namespace PhpProbe\Helper;

/**
 * Class ProbeHelper
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Helper
 */
class ProbeHelper
{
    /**
     * @param string $type Probe type
     *
     * @return string
     */
    public static function getClassNameFromType($type)
    {
        $rootNamespace = substr(__NAMESPACE__, 0, strpos(__NAMESPACE__, "\\"));
        $className = "\\".$rootNamespace."\\Probe\\".$type."Probe";
        return $className;
    }
}
