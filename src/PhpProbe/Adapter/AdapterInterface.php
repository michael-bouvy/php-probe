<?php

namespace PhpProbe\Adapter;

use PhpProbe\Adapter\Reponse\AdapterResponseInterface;

/**
 * Interface AdapterInterface
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
interface AdapterInterface
{
    /**
     * Returns true on success or error message
     *
     * @param array $parameters
     *
     * @return AdapterInterface
     */
    public function check(array $parameters);

    /**
     * Get the response matching with adapter to be processed by probe
     *
     * @return AdapterResponseInterface
     */
    public function getResponse();
}
