<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\FsockopenAdapter;
use PhpProbe\Adapter\Reponse\TcpAdapterResponse;

/**
 * Class TcpProbe
 *
 * @method \PhpProbe\Probe\TcpProbe host($value)
 * @method \PhpProbe\Probe\TcpProbe port($value)
 * @method \PhpProbe\Probe\TcpProbe timeout($value)
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class TcpProbe extends AbstractProbe implements ProbeInterface
{
    /**
     * @var array Expected options
     */
    protected $expectedOptions = array(
        array('name' => 'host', 'required' => true, 'type' => 'string'),
        array('name' => 'port', 'required' => true, 'type' => 'integer'),
        array('name' => 'timeout', 'required' => true, 'type' => 'integer', 'default' => 2)
    );

    /**
     * {@inheritdoc}
     */
    public function check()
    {
        $this->checkConfiguration();

        $this->adapter->check($this->options);

        /** @var TcpAdapterResponse $response */
        $response = $this->adapter->getResponse();

        if ($response->isSuccessful()) {
            $this->succeeded();
            return;
        }

        $this->failed($response->getError());
    }

    /**
     * Get probe's default adapter
     *
     * @return AdapterInterface
     */
    public function getDefaultAdapter()
    {
        return new FsockopenAdapter();
    }
}
