<?php

namespace PhpProbe\Check;

use \PhpProbe\Adapter\Reponse\AbstractAdapterResponse;

/**
 * Class AbstractCheck
 *
 * @author  Julien CHICHIGNOUD <julien.chichignoud@gmail.com>
 * @package PhpProbe\Check
 */
abstract class AbstractCheck implements CheckInterface
{
    /**
     * @var array
     */
    protected $checkList = array();

    /**
     * Add a criterion to this checker
     *
     * @param string $type
     * @param mixed  $parameter
     *
     * @return $this
     */
    public function addCriterion($type, $parameter)
    {
        $this->checkList[] = array('type' => $type, 'param' => $parameter);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function check(AbstractAdapterResponse $response)
    {
        $errors = array();
        foreach ($this->checkList as $_item) {
            $checkFunction = 'check'.$_item['type'];
            $result = $this->$checkFunction($response, $_item['param']);
            if ($result !== true) {
                $errors[] = $result;
            }
        }

        return $errors;
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
                "Expected value '%s' for '%s', got '%s'",
                $expected,
                $name,
                $actual
            );

            return $reason;
        }

        return true;
    }
}