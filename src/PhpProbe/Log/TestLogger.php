<?php

namespace PhpProbe\Log;

use Psr\Log\AbstractLogger;

/**
 * Class TestLogger
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Log
 */
class TestLogger extends AbstractLogger
{
    /**
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function log($level, $message, array $context = array())
    {
        return;
    }
}
