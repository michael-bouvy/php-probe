<?php

namespace PhpProbe\Adapter\Reponse;

/**
 * Class NullAdapterResponse
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter\Reponse
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
