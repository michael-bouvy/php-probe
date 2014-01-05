<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;

/**
 * Interface ProbeInterface
 *
 * @author Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
interface ProbeInterface
{
    /**
     * @param string           $name    Probe name
     * @param array            $options Configuration array (optional)
     * @param AdapterInterface $adapter Adapter to use (optional)
     */
    public function __construct($name, $options = array(), AdapterInterface $adapter = null);

    /**
     * @param array $options
     *
     * @return $this
     */
    public function configure(array $options);

    /**
     * @return void
     */
    public function check();

    /**
     * @return bool
     */
    public function hasFailed();

    /**
     * @return bool
     */
    public function hasSucceeded();

    /**
     * @return string
     */
    public function getErrorMessage();

    /**
     * Get a unique hash for probe to identify it
     *
     * @return string
     */
    public function getHash();

    /**
     * Get the probe's name
     *
     * @return string
     */
    public function getName();

    /**
     * @param AdapterInterface $adapter
     *
     * @return mixed
     */
    public function setAdapter(AdapterInterface $adapter);

    /**
     * @param array $options
     *
     * @return void
     */
    public function checkConfiguration($options = array());
}
