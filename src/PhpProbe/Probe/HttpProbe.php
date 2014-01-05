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
     * @var array Default options
     */
    protected $options = array(
        'timeout'          => 2,
        'expectedHttpCode' => 200
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

        $result = $this->adapter->check(
            array(
                'url'              => $this->options['url'],
                'timeout'          => $this->options['timeout'],
                'expectedHttpCode' => $this->options['expectedHttpCode']
            )
        );

        if ($result === true) {
            $this->succeeded();
        } else {
            $this->failed($result);
        }
    }

    /**
     * Check configuration values
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

        if (!isset($this->options['url'])) {
            throw new ConfigurationException('No url specified');
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
