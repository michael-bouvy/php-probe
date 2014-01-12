<?php

namespace PhpProbe\Check;

use PhpProbe\Adapter\Reponse\AbstractAdapterResponse;
use \PhpProbe\Adapter\Reponse\HttpAdapterResponse;

/**
 * Class HttpCheck
 *
 * @author  Julien CHICHIGNOUD <julien.chichignoud@gmail.com>
 * @package PhpProbe\Check
 */
class HttpCheck extends AbstractCheck
{
    /**
     * Check the http response code
     *
     * @param AbstractAdapterResponse $response The response object
     * @param int                     $expected Expected code
     *
     * @return bool|string
     */
    protected function checkHttpCode($response, $expected)
    {
        return $this->checkValue('httpCode', $expected, $response->getHttpCode());
    }

    /**
     * Check content response's content
     *
     * @param HttpAdapterResponse $response The response object
     * @param string              $search   Search criterion
     *
     * @return mixed
     */
    protected function checkContent(HttpAdapterResponse $response, $search)
    {
        if (!preg_match('#' . $search . '#i', $response->getContent())) {
            $reason = sprintf("Expected content '%s' not found in response.", $search);
            return $reason;
        }

        return true;
    }
}
