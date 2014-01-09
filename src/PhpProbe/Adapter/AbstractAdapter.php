<?php

namespace PhpProbe\Adapter;

use PhpProbe\Adapter\Reponse\AdapterResponseInterface;

/**
 * Class AbstractAdapter
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * @var AdapterResponseInterface
     */
    protected $response = null;

    /**
     * @param Reponse\AdapterResponseInterface $response
     */
    protected function setResponse(AdapterResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return AdapterResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }
}
