<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\PhpMysqlAdapter;
use PhpProbe\Adapter\Response\DatabaseAdapterResponse;

/**
 * Class DatabaseProbe
 *
 * @method \PhpProbe\Probe\DatabaseProbe host($value)
 * @method \PhpProbe\Probe\DatabaseProbe database($value)
 * @method \PhpProbe\Probe\DatabaseProbe user($value)
 * @method \PhpProbe\Probe\DatabaseProbe password($value)
 * @method \PhpProbe\Probe\DatabaseProbe port($value)
 * @method \PhpProbe\Probe\DatabaseProbe timeout($value)
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class DatabaseProbe extends AbstractProbe implements ProbeInterface
{
    /**
     * @var array Expected options
     */
    protected $expectedOptions = array(
        'host'     => array('name' => 'host', 'required' => true, 'type' => 'string', 'default' => 'localhost'),
        'database' => array('name' => 'database', 'required' => false, 'type' => 'string'),
        'user'     => array('name' => 'user', 'required' => true, 'type' => 'string'),
        'password' => array('name' => 'password', 'required' => false, 'type' => 'string', 'default' => ''),
        'name'     => array('name' => 'port', 'required' => false, 'type' => 'integer'),
        'port'     => array('name' => 'timeout', 'required' => false, 'type' => 'integer', 'default' => 2),
    );

    /**
     * @return AdapterInterface
     */
    public function getDefaultAdapter()
    {
        return new PhpMysqlAdapter();
    }
}
