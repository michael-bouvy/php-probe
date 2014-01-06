<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\PhpMysqlAdapter;
use PhpProbe\Exception\ConfigurationException;

/**
 * Class DatabaseProbe
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
        array('name' => 'host', 'required' => true, 'type' => 'string', 'default' => 'localhost'),
        array('name' => 'database', 'required' => false, 'type' => 'string'),
        array('name' => 'user', 'required' => true, 'type' => 'string'),
        array('name' => 'password', 'required' => false, 'type' => 'string', 'default' => ''),
        array('name' => 'port', 'required' => false, 'type' => 'integer'),
        array('name' => 'timeout', 'required' => false, 'type' => 'integer', 'default' => 2),
    );

    /**
     * @param string           $name
     * @param array            $options
     * @param AdapterInterface $adapter
     */
    public function __construct($name, $options = array(), AdapterInterface $adapter = null)
    {
        $this->name = $name;
        $this->configure($options);

        if (!is_null($adapter)) {
            $this->setAdapter($adapter);
        } else {
            $this->adapter = new PhpMysqlAdapter();
        }

        return $this;
    }

    /**
     * Check probe using defined adapter
     *
     * @throws ConfigurationException
     * @return void
     */
    public function check()
    {
        $this->checkConfiguration();

        $result = $this->adapter->check($this->options);

        if ($result === true) {
            $this->succeeded();
        } else {
            $this->failed($result);
        }
    }
}
