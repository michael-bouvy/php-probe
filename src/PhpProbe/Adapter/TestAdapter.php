<?php

namespace PhpProbe\Adapter;

use PhpProbe\Adapter\Reponse\AdapterResponseInterface;
use PhpProbe\Adapter\Reponse\TestAdapterResponse;

/**
 * Class TestAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
class TestAdapter extends AbstractAdapter implements AdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function check(array $parameters = array())
    {
        $response = new TestAdapterResponse();
        $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);
        $this->setResponse($response);

        return $this;
    }
}
