<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Check\CheckInterface;
use PhpProbe\Exception\ConfigurationException;
use Psr\Log\LoggerInterface;

/**
 * Interface ProbeInterface
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
interface ProbeInterface
{
    const NO_REASON_FAIL_MESSAGE = 'Failed with no reason ...';
    const STATUS_SUCCESS         = 'success';
    const STATUS_FAILED          = 'failed';
    const STATUS_UNKNOWN         = 'unknown';

    /**
     * @param string           $name    Probe name
     * @param array            $options Configuration array (optional)
     * @param AdapterInterface $adapter Adapter to use (optional)
     */
    public function __construct($name, $options = array(), AdapterInterface $adapter = null);

    /**
     * Configure probe's options (upon it's expectedOptions array property)
     *
     * @param array $options
     *
     * @return $this
     */
    public function configure(array $options);

    /**
     * Use specified adapter and run probe and associated checkers
     *
     * @throws ConfigurationException
     * @return void
     */
    public function check();

    /**
     * Returns true if probe has failed ; ie. connection could not be established
     * OR one or more checkers associated with probe didn't success
     *
     * @return bool
     */
    public function hasFailed();

    /**
     * Returns false if probe has succeeded ; ie. connection has been established
     * AND all checkers (if any) associated with probe did success
     *
     * @return bool
     */
    public function hasSucceeded();

    /**
     * Get an array of the error messages associated with the probe and it's checkers
     *
     * This array will be empty if probe is successful (see hasSucceeded() method)
     *
     * @return array
     */
    public function getErrorMessages();

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
     * Set the adapter to use with the probe
     *
     * @param AdapterInterface $adapter
     *
     * @return mixed
     */
    public function setAdapter(AdapterInterface $adapter);

    /**
     * Get the current associated adapter for probe
     *
     * @return AdapterInterface
     */
    public function getAdapter();

    /**
     * Get probe's default adapter, as setting an adapter is not mandatory
     *
     * @return AdapterInterface
     */
    public function getDefaultAdapter();

    /**
     * Check configurations options (passed through constructor or configure() method)
     * against expectedOptions array property.
     *
     * @throws ConfigurationException
     * @return void
     */
    public function checkConfiguration();

    /**
     * Add a checker object for this probe
     *
     * @param CheckInterface $check The checker
     *
     * @return $this
     */
    public function addChecker(CheckInterface $check);

    /**
     * Get the full array containing all expected/available options
     * of the probe.
     *
     * @return array
     */
    public function getExpectedOptions();

    /**
     * Set a PSR-3 compliant logger
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * Set PSR-3 compliant priority level (eg. for notifications, display ...) for checker
     *
     * @param string $level
     *
     * @return mixed
     */
    public function setLevel($level);

    /**
     * Get PSR-3 compliant priority level of checker
     *
     * @return string
     */
    public function getLevel();
}
