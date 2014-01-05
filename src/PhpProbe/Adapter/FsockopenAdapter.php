<?php

namespace PhpProbe\Adapter;

/**
 * Class FsockopenAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
class FsockopenAdapter implements AdapterInterface
{
    /**
     * Check connection using PHP's fsockopen() function
     *
     * @param array $parameters
     *
     * @return bool|string
     */
    public function check(array $parameters)
    {
        $res = fsockopen($parameters['host'], $parameters['port'], $errno, $errstr, $parameters['timeout']);
        if ($res === false) {
            return sprintf('%d : %s', $errno, $errstr);
        }
        return true;
    }
}
