<?php

namespace PhpProbe\Adapter;

/**
 * Interface AdapterInterface
 *
 * @author Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
interface AdapterInterface
{
    /**
     * @param array $parameters
     * @return bool
     */
    public function check(array $parameters);
}
