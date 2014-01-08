<?php

namespace PhpProbe\Adapter;
use Symfony\Component\Process\Process;

/**
 * Class NetcatAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
class NetcatAdapter implements AdapterInterface
{
    /**
     * Check connection using netcat utility
     *
     * @param array $parameters
     *
     * @return bool|string
     */
    public function check(array $parameters)
    {
        $command = "nc -vz -w " . $parameters['timeout'] . " " . $parameters['host'] . " " . $parameters['port'];

        $process = new Process($command);
        // Keep 2 seconds margin from request timeout
        $process->setTimeout($parameters['timeout'] + 2);
        $process->run();

        if (!$process->isSuccessful()) {
            return sprintf('%s', $process->getErrorOutput());
        }

        return true;
    }
}
