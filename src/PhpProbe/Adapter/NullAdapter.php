<?php

namespace PhpProbe\Adapter;

use PhpProbe\Adapter\Response\NullAdapterResponse;

/**
 * Class NullAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
class NullAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(array $parameters)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return new NullAdapterResponse();
    }
}
