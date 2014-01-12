<?php

namespace PhpProbe\Adapter;

use PhpProbe\Adapter\Reponse\AdapterResponseInterface;
use PhpProbe\Adapter\Reponse\TcpAdapterResponse;

/**
 * Class FsockopenAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 *
 * @codeCoverageIgnore
 */
class FsockopenAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * Check connection using PHP's fsockopen() function
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function check(array $parameters)
    {
        $timerStart = microtime();
        $res        = fsockopen($parameters['host'], $parameters['port'], $errno, $errstr, $parameters['timeout']);
        $timerEnd   = microtime();
        $duration   = $timerEnd - $timerStart;

        $response = new TcpAdapterResponse();

        if ($res === false) {
            $error = sprintf('%d : %s', $errno, $errstr);
            $response->setError($error);
            $response->setStatus(AdapterResponseInterface::STATUS_FAILED);
        } else {
            $response->setResponseTime($duration);
            $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);
        }

        $this->setResponse($response);

        return $this;
    }
}
