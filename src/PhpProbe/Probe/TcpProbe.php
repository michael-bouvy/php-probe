<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\FsockopenAdapter;
use PhpProbe\Exception\ConfigurationException;

/**
 * Class TcpProbe
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class TcpProbe extends AbstractProbe implements ProbeInterface
{
    /**
     * @var array Expected options
     */
    protected $expectedOptions = array(
        array('name' => 'host', 'required' => true,'type' => 'string'),
        array('name' => 'port', 'required' => true,'type' => 'integer'),
        array('name' => 'timeout', 'required' => true,'type' => 'integer', 'default' => 2)
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
            $this->adapter = new FsockopenAdapter();
        }

        return $this;
    }

    /**
     * Check probe using specified adapter
     *
     * @throws ConfigurationException
     * @return void
     */
    public function check()
    {
        $this->checkConfiguration();

        $result = $this->adapter->check(
            array(
                'host'    => $this->options['host'],
                'port'    => $this->options['port'],
                'timeout' => $this->options['timeout']
            )
        );

        if ($result === true) {
            $this->succeeded();
        } else {
            $this->failed($result);
        }
    }



    /**
     * Set host to check
     *
     * @param string $host hostname to check
     *
     * @return $this
     */
    public function host($host)
    {
        $this->options['host'] = $host;
        return $this;
    }

    /**
     * Set port to check
     *
     * @param $port
     *
     * @return $this
     */
    public function port($port)
    {
        $this->options['port'] = $port;
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
}
