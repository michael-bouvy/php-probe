<?php

namespace PhpProbe\Adapter;

/**
 * Class NullAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
class NullAdapter implements AdapterInterface
{
    /**
     * Always return true
     *
     * @param array $parameters
     *
     * @return bool
     */
    public function check(array $parameters)
    {
        return true;
    }
}
