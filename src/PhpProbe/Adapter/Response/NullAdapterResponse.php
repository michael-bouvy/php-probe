<?php

namespace PhpProbe\Adapter\Response;

/**
 * Class NullAdapterResponse
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter\Response
 */
class NullAdapterResponse extends AbstractAdapterResponse
{
    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return AdapterResponseInterface::STATUS_SUCCESSFUL;
    }
}
