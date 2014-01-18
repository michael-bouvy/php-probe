<?php

namespace PhpProbe\Helper;

use PhpProbe\Exception\ExitException;

/**
 * Class CliHelper
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Helper
 */
class CliHelper
{
    /**
     * Exit with given status code
     *
     * @param int $status Exit status
     *
     * @return void
     */
    public static function doExit($status = 0)
    {
        exit($status);
    }

    /**
     * Get a handler for ExitException
     *
     * @return \Closure
     */
    public static function getExitExceptionHandler()
    {
        return function ($exception) {
            if ($exception instanceof ExitException) {
                CliHelper::doExit($exception->getCode());
            }
            throw $exception;
        };
    }
}
