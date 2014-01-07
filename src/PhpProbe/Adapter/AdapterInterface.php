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
     * Returns true on success or error message
     *
     * @param array $parameters
     * @return bool|string
     */
    public function check(array $parameters);
}
