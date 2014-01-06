<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\PhpCurlAdapter;
use PhpProbe\Exception\ConfigurationException;

/**
 * Class HttpProbe
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class HttpProbe extends AbstractProbe implements ProbeInterface
{
    /**
     * @var array Expected options
     */
    protected $expectedOptions = array(
        array('name' => 'url', 'required' => true, 'type' => 'string'),
        array('name' => 'timeout', 'required' => true, 'type' => 'integer', 'default' => 2),
        array('name' => 'expectedHttpCode', 'required' => true, 'type' => 'integer', 'default' => 200),
        array('name' => 'contains', 'required' => false, 'type' => 'string')
    );

    /**
     * @param string           $name
     * @param array            $options
     * @param AdapterInterface $adapter
     */
    public function __construct($name, $options = array(), AdapterInterface $adapter = null)
    {
        $this->name = $name;
        $this->configure($options);

        if (!is_null($adapter)) {
            $this->setAdapter($adapter);
        } else {
            $this->adapter = new PhpCurlAdapter();
        }

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

        $result = $this->adapter->check($this->options);

        if ($result === true) {
            $this->succeeded();
        } else {
            $this->failed($result);
        }
    }

    /**
     * Set URL to check
     *
     * @param string $url URL to check
     *
     * @return $this
     */
    public function url($url)
    {
        $this->options['url'] = $url;
        return $this;
    }

    /**
     * Set request timeout
     *
     * @param $timeout
     *
     * @return $this
     */
    public function timeout($timeout)
    {
        $this->options['timeout'] = $timeout;
        return $this;
    }

    /**
     * Set expected HTTP response code
     *
     * @param int $code
     *
     * @return $this
     */
    public function expectedHttpCode($code)
    {
        $this->options['expectedHttpCode'] = $code;
        return $this;
    }
}
