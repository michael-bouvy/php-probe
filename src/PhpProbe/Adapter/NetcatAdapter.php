<?php

namespace PhpProbe\Adapter;

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
        $command =
            "nc -vz -w " . $parameters['timeout'] . " "
            . $parameters['host'] . " " . $parameters['port'] . " 2>&1";

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            return sprintf('%s', implode($output, " - "));
        }
        return true;
    }
}
