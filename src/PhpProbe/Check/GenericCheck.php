<?php

namespace PhpProbe\Check;

use PhpProbe\Adapter\Response\AbstractAdapterResponse;

/**
 * Class GenericCheck
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Check
 */
class GenericCheck extends AbstractCheck
{
    /**
     * Check the response time
     *
     * @param AbstractAdapterResponse $response The response object
     * @param int                     $expected Expected max. response time
     *
     * @return bool|string
     */
    protected function checkResponseTime($response, $expected)
    {
        $responseTime = $response->getResponseTime();
        if ($responseTime > $expected) {
            return sprintf("Response time higher than expected : '%s' > '%s'", $responseTime, $expected);
        }

        return true;
    }
}
