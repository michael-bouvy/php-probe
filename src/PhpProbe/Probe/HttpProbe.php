<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\PhpCurlAdapter;

/**
 * Class HttpProbe
 *
 * @method \PhpProbe\Probe\HttpProbe url($value)
 * @method \PhpProbe\Probe\HttpProbe timeout($value)
 * @method \PhpProbe\Probe\HttpProbe headers($array)
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class HttpProbe extends AbstractProbe implements ProbeInterface
{
    /**
     * @var array Expected options
     */
    protected $expectedOptions = array(
        'url'      => array('name' => 'url', 'required' => true, 'type' => 'string'),
        'timeout'  => array('name' => 'timeout', 'required' => true, 'type' => 'integer', 'default' => 2),
        'headers'  => array('name' => 'headers', 'required' => true, 'type' => 'array', 'default' => array()),
        'unsecure' => array('name' => 'unsecure', 'required' => true, 'type' => 'boolean', 'default' => false)
    );

    /**
     * Get probe's default adapter
     *
     * @return AdapterInterface
     */
    public function getDefaultAdapter()
    {
        return new PhpCurlAdapter();
    }
}
