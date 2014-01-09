<?php

namespace PhpProbe\Helper;

/**
 * Class HttpHelper
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Helper
 */
class HttpHelper
{
    /**
     * Set fail HTTP headers according to probes' results
     *
     * @param bool $force
     *
     * @return void
     */
    public static function setFailHttpHeader($force = false)
    {
        if (php_sapi_name() != 'cli' && $force === false) {
            header("Cache-Control: no-cache, max-age=0");
            header("HTTP/1.1 503 Service Unavailable", false, 503);
        }
    }

    /**
     * Set success HTTP headers according to probes' results
     *
     * @param bool $force
     *
     * @return void
     */
    public static function setSuccessHttpHeader($force = false)
    {
        if (php_sapi_name() != 'cli' && $force === false) {
            header("Cache-Control: no-cache, max-age=0");
        }
    }
}
