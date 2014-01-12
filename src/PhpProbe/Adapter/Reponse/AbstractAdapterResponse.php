<?php

namespace PhpProbe\Adapter\Reponse;

use PhpProbe\Exception\ConfigurationException;

/**
 * Class AbstractAdapterResponse
 *
 * @method void   setError()
 * @method string getError()
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Adapter\Reponse
 */
class AbstractAdapterResponse implements AdapterResponseInterface
{
    /**
     * @var array
     */
    protected $propertyBag = array();

    /**
     * @var string
     */
    protected $status;

    /**
     * Populate propertyBag with data passed to constructor
     *
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        foreach ($data as $key => $value) {
            $setter = 'set' . ucfirst($key);
            $this->{$setter}($value);
        }
    }

    /**
     * Set response's status
     *
     * Might be one of :
     * - AdapterResponseInterface::STATUS_SUCCESSFUL
     * - AdapterResponseInterface::STATUS_FAILED
     * - AdapterResponseInterface::STATUS_UNKNOWN
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        if (!in_array(
            $status,
            array(
                AdapterResponseInterface::STATUS_SUCCESSFUL,
                AdapterResponseInterface::STATUS_FAILED,
                AdapterResponseInterface::STATUS_UNKNOWN
            )
        )
        ) {
            return;
        }
        $this->status = $status;
    }

    /**
     * Get response's status
     *
     * @see setStatus()
     *
     * @return string
     */
    public function getStatus()
    {
        return isset($this->status) ? $this->status : AdapterResponseInterface::STATUS_UNKNOWN;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return (isset($this->status) && $this->status == AdapterResponseInterface::STATUS_SUCCESSFUL);
    }

    /**
     * Magic getter/setter for Response properties : put them
     * in the $propertyBag
     *
     * @param string $method
     * @param array  $arguments
     *
     * @throws ConfigurationException
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        $action = substr($method, 0, 3);
        if (!in_array($action, array('get', 'set'))) {
            return null;
        }

        $propertyName = substr($method, 3);

        if ($action == 'set') {
            $propertyValue                    = array_shift($arguments);
            $this->propertyBag[$propertyName] = $propertyValue;
        } else {
            if (!isset($this->propertyBag[$propertyName])) {
                throw new ConfigurationException(sprintf("Response property '%s' not available.", $propertyName));
            }
            return $this->propertyBag[$propertyName];
        }
        return null;
    }
}
