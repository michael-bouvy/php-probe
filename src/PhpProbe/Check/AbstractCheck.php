<?php

namespace PhpProbe\Check;

use \PhpProbe\Adapter\Reponse\AbstractAdapterResponse;
use Psr\Log\LogLevel;

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
     * @var string
     */
    protected $level = LogLevel::INFO;

    /**
     * {@inheritdoc}
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * {@inheritdoc}
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * {@inheritdoc}
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
        foreach ($this->checkList as $item) {
            $checkFunction = 'check' . ucfirst($item['type']);
            if (!method_exists($this, $checkFunction)) {
                throw new \RuntimeException(sprintf("Method '%s' does not exist", $checkFunction));
            }
            $result = $this->{$checkFunction}($response, $item['param']);
            if ($result !== true) {
                $errors[] = $result;
            }
        }

        return $errors;
    }

    /**
     * Check a value based on it's expected and actual values
     *
     * @param string $name
     * @param mixed  $expected
     * @param mixed  $actual
     *
     * @return bool|string
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
