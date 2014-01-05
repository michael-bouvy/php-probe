<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;

/**
 * Class TestProbe
 *
 * Used for testing purposes
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class TestProbe implements ProbeInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var AdapterInterface
     */
    protected $adapter = null;

    /**
     * @param string           $name    Probe name
     * @param array            $options Configuration array (optional)
     * @param AdapterInterface $adapter Adapter to use (optional)
     */
    public function __construct($name, $options = array(), AdapterInterface $adapter = null)
    {
        $this->name = $name;
        $this->configure($options);
        if (!is_null($adapter)) {
            $this->setAdapter($adapter);
        }
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function configure(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return void
     */
    public function check()
    {
        return;
    }

    /**
     * @return bool
     */
    public function hasFailed()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function hasSucceeded()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return 'Error';
    }

    /**
     * Get a unique hash for probe to identify it
     *
     * @return string
     */
    public function getHash()
    {
        return md5($this->name);
    }

    /**
     * Get the probe's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param AdapterInterface $adapter
     *
     * @return mixed
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param array $options
     *
     * @return void
     */
    public function checkConfiguration($options = array())
    {
        return;
    }

    /**
     * @return array
     */
    public function getExpectedOptions()
    {
        return array();
    }
}
