<?php

namespace PhpProbe\Adapter;

use PhpProbe\Adapter\Reponse\AdapterResponseInterface;
use PhpProbe\Adapter\Reponse\TcpAdapterResponse;
use Symfony\Component\Process\Process;

/**
 * Class NetcatAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
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

        $process = new Process($command);
        // Keep 2 seconds margin from request timeout
        $process->setTimeout($parameters['timeout'] + 2);
        $process->run();

        $response = new TcpAdapterResponse();

        if (!$process->isSuccessful()) {
            $response->setStatus(AdapterResponseInterface::STATUS_FAILED);
            $response->setError(sprintf('%s', $process->getErrorOutput()));
        } else {
            $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);
        }

        $this->setResponse($response);

        return $this;
    }
}
