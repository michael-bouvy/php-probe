<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Exception\ConfigurationException;

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
     * @var array Expected options array
     */
    protected $expectedOptions = array();

    /**
     * Configure probe
     *
     * @param array $options
     *
     * @return $this
     */
    public function configure(array $options)
    {
        foreach ($this->getExpectedOptions() as $expectedOption) {
            if (isset($expectedOption['name'])
                && isset($expectedOption['default'])
                && !isset($this->options[$expectedOption['name']])
            ) {
                $this->options[$expectedOption['name']] = $expectedOption['default'];
            }
        }
        $this->options = array_merge($this->options, $options);
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

    /**
     * Check probe's configuration
     *
     * @throws ConfigurationException
     */
    public function checkConfiguration()
    {
        foreach ($this->getExpectedOptions() as $expectedOption) {
            $name = $expectedOption['name'];

            if (empty($name)) {
                throw new ConfigurationException("Expected options must have a name.");
            }

            if (isset($expectedOption['required'])
                && $expectedOption['required'] === true
                && !isset($this->options[$name])
            ) {
                throw new ConfigurationException(
                    sprintf(
                        "%s probe : missing option '%s'.",
                        $this->getName(),
                        $name
                    )
                );
            }

            if (isset($this->options[$name])) {
                $currentOption = $this->options[$name];
                if (isset($expectedOption['type']) && gettype($currentOption) != $expectedOption['type']) {
                    throw new ConfigurationException(
                        sprintf(
                            "%s probe Bad value type for '%s' ; expected %s, got %s.",
                            $this->getName(),
                            $name,
                            $expectedOption['type'],
                            gettype($currentOption)
                        )
                    );
                }
            }
        }

        $this->checkAdapter();
    }

    /**
     * Check if an adapter is set
     *
     * TODO : check for compatibility between probe and adapter
     *
     * @throws ConfigurationException
     */
    public function checkAdapter()
    {
        if (is_null($this->adapter)) {
            throw new ConfigurationException('No adapter specified');
        }
    }

    /**
     * Get the expected options
     *
     * @return array
     */
    public function getExpectedOptions()
    {
        return $this->expectedOptions;
    }
}
