<?php

namespace PhpProbe\Adapter\Response;

/**
 * Class TestAdapterResponse
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter\Response
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
