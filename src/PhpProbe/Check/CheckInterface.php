<?php

namespace PhpProbe\Check;

use \PhpProbe\Adapter\Reponse\AbstractAdapterResponse;

/**
 * Interface CheckInterface
 *
 * @author  Julien CHICHIGNOUD <julien.chichignoud@gmail.com>
 * @package PhpProbe\Check
 */
interface CheckInterface
{
    /**
     * Check a response against the defined criteria
     *
     * @param AbstractAdapterResponse $response
     *
     * @return array
     */
    public function check(AbstractAdapterResponse $response);

    /**
     * Add a check criterion to checker
     *
     * @param string $type
     * @param string $parameter
     *
     * @return $this
     */
    public function addCriterion($type, $parameter);

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
