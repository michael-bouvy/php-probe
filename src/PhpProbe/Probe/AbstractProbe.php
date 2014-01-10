<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Check\CheckInterface;
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
     * @var array Checkers list
     */
    protected $checkers = array();

    /**
     * @var array Options array
     */
    protected $options = array();

    /**
     * @var array Expected options array
     */
    protected $expectedOptions = array();

    /**
     * @param string           $name
     * @param array            $options
     * @param AdapterInterface $adapter
     */
    public function __construct($name, $options = array(), AdapterInterface $adapter = null)
    {
        $this->name = $name;
        $this->configure($options);

        if (is_null($adapter)) {
            $adapter = $this->getDefaultAdapter();
        }
        $this->setAdapter($adapter);

        return $this;
    }

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
     * Check probe using defined adapter
     *
     * @throws ConfigurationException
     * @return void
     */
    public function check()
    {
        $this->checkConfiguration();

        $this->adapter->check($this->options);
        /** @var AbstractAdapterResponse $response */
        $response = $this->adapter->getResponse();

        $errors = array();
        foreach ($this->checkers as $checker) {
            $_errors = $checker->check($response);
            $errors = array_merge($errors, $_errors);
        }

        if (!empty($errors)) {
            $this->failed(implode($errors, "\n"));
        }

        if ($response->isSuccessful()) {
            $this->succeeded();
            return;
        }

        $this->failed(ProbeInterface::NO_REASON_FAIL_MESSAGE);
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
     * Add a checker object for this probe
     *
     * @param CheckInterface $check The checker
     *
     * @return $this
     */
    public function addChecker(CheckInterface $check)
    {
        $this->checkers[] = $check;

        return $this;
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

    /**
     * Dynamically set options if method call matches with an expected option
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($name, $arguments)
    {
        $value           = array_shift($arguments);
        $expectedOptions = $this->getExpectedOptions();
        foreach ($expectedOptions as $expectedOption) {
            if ($expectedOption['name'] == $name) {
                $this->options[$name] = $value;
            }
        }

        return $this;
    }

    /**
     * Get the adapter associated with probe
     *
     * @return AdapterInterface|null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Check a value based on it's expected and actual values
     *
     * @param $name
     * @param $expected
     * @param $actual
     */
    protected function checkValue($name, $expected, $actual)
    {
        if (isset($expected) && $expected != $actual) {
            $reason = sprintf(
                "Epected value '%s' for '%s', got '%s'",
                $expected,
                $name,
                $actual
            );

            $this->failed($reason);
            return;
        }
    }
}
