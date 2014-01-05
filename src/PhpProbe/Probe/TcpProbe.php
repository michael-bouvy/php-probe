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
     * @var array Default options
     */
    protected $options = array(
        'timeout' => 2
    );

    /**
     * @param string           $name
     * @param array            $options
     * @param AdapterInterface $adapter
     */
    public function __construct($name, $options = array(), AdapterInterface $adapter = null)
    {
        $this->name    = $name;
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
     * Check probe's configuration
     *
     * @param array $options
     *
     * @throws ConfigurationException
     */
    public function checkConfiguration($options = array())
    {
        if (count($options)) {
            $this->configure($options);
        }

        if (is_null($this->adapter)) {
            throw new ConfigurationException('No adapter specified');
        }

        if (!isset($this->options['host'])) {
            throw new ConfigurationException('No host specified');
        }

        if (isset($this->options['port']) && !is_long($this->options['port'])) {
            throw new ConfigurationException(
                sprintf(
                    'Bad value type for port : expected long, got %s (value = %s).',
                    gettype($this->options['port']),
                    $this->options['port']
                )
            );
        }

        if (!isset($this->options['port'])) {
            throw new ConfigurationException('No port specified or invalid type.');
        }

        if (!isset($this->options['timeout'])
            || (isset($this->options['timeout']) && !is_int($this->options['timeout']))
        ) {
            throw new ConfigurationException(
                sprintf(
                    'Bad value type for timeout : expected int, got %s (value = %s).',
                    gettype($this->options['timeout']),
                    $this->options['timeout']
                )
            );
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
