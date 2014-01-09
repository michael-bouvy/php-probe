<?php

namespace PhpProbe\Adapter\Reponse;

/**
 * Class TestAdapterResponse
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter\Reponse
 */
class TestAdapterResponse extends AbstractAdapterResponse
{
    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return AdapterResponseInterface::STATUS_SUCCESSFUL;
    }
}
