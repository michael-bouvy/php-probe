<?php

namespace PhpProbe\Adapter;

/**
 * Class TestAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
class TestAdapter implements AdapterInterface
{
    /**
     * @param array $parameters
     *
     * @return bool|string
     */
    public function check(array $parameters = array())
    {
        return true;
    }
}
