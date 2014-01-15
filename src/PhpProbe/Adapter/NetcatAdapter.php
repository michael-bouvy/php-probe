<?php

namespace PhpProbe\Adapter;

use PhpProbe\Adapter\Response\AdapterResponseInterface;
use PhpProbe\Adapter\Response\TcpAdapterResponse;
use Symfony\Component\Process\Process;

/**
 * Class NetcatAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 *
 * @codeCoverageIgnore
 */
class NetcatAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * Check connection using netcat utility
     *
     * @param array $parameters
     *
     * @return $this
     */
    public function check(array $parameters)
    {
        $command = "nc -vz -w " . $parameters['timeout'] . " " . $parameters['host'] . " " . $parameters['port'];

        $timerStart = microtime();
        $process    = new Process($command);
        // Keep 2 seconds margin from request timeout
        $process->setTimeout($parameters['timeout'] + 2);
        $process->run();
        $timerEnd = microtime();
        $duration = $timerEnd - $timerStart;

        $response = new TcpAdapterResponse();

        if (!$process->isSuccessful()) {
            $response->setStatus(AdapterResponseInterface::STATUS_FAILED);
            $response->setError(sprintf('%s', $process->getErrorOutput()));
        } else {
            $response->setResponseTime($duration);
            $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);
        }

        $this->setResponse($response);

        return $this;
    }
}
