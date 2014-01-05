<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;

/**
 * Class AbstractProbe
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
abstract class AbstractProbe implements ProbeInterface
{
    /**
     * @var bool
     */
    protected $failure = false;

    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var string
     */
    protected $error;

    /**
     * @var string Probe's name
     */
    protected $name;

    /**
     * @var AdapterInterface
     */
    protected $adapter = null;

    /**
     * @var array Options array
     */
    protected $options = array();

    /**
     * Configure probe
     *
     * @param array $options
     *
     * @return $this
     */
    public function configure(array $options)
    {
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
        return $this;
    }

    /**
     * Get unique hash for probe
     *
     * @return string
     */
    public function getHash()
    {
        return md5($this->name);
    }

    /**
     * Get probe's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set probe's name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set probe as failed
     *
     * @param string
     *
     * @return void
     */
    public function failed($reason)
    {
        $this->error   = $reason;
        $this->failure = true;
    }

    /**
     * Check if probe has failed
     *
     * @return bool
     */
    public function hasFailed()
    {
        return $this->failure;
    }

    /**
     * Check if probe has succeeded
     *
     * @return bool
     */
    public function hasSucceeded()
    {
        return $this->success;
    }

    /**
     * Set probe as success
     *
     * @return void
     */
    public function succeeded()
    {
        $this->success = true;
    }

    /**
     * Get error message if probe has failed
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error;
    }

    /**
     * Set adapter to use ; it must be compatible with the probe (use the same options)
     *
     * @param AdapterInterface $adapter
     *
     * @return $this
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }
}
