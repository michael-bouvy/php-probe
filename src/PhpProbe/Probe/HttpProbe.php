<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\PhpCurlAdapter;
use PhpProbe\Adapter\Reponse\HttpAdapterResponse;

/**
 * Class HttpProbe
 *
 * @method \PhpProbe\Probe\HttpProbe url($value)
 * @method \PhpProbe\Probe\HttpProbe timeout($value)
 * @method \PhpProbe\Probe\HttpProbe expectedHttpCode($value)
 * @method \PhpProbe\Probe\HttpProbe contains($value)
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
        'url'              => array('name' => 'url', 'required' => true, 'type' => 'string'),
        'timeout'          => array('name' => 'timeout', 'required' => true, 'type' => 'integer', 'default' => 2),
        'expectedHttpCode' => array(
            'name' => 'expectedHttpCode', 'required' => true, 'type' => 'integer', 'default' => 200
        ),
        'contains'         => array('name' => 'contains', 'required' => false, 'type' => 'string')
    );

    /**
     * {@inheritdoc}
     */
    public function check()
    {
        $this->checkConfiguration();

        $this->adapter->check($this->options);
        /** @var HttpAdapterResponse $response */
        $response = $this->adapter->getResponse();

        $this->checkValue('httpCode', $this->options['expectedHttpCode'], $response->getHttpCode());
        if (isset($this->options['contains'])) {
            $this->checkContent($this->options['contains'], $response->getContent());
        }

        if ($response->isSuccessful()) {
            $this->succeeded();
            return;
        }

        $this->failed(ProbeInterface::NO_REASON_FAIL_MESSAGE);
    }

    /**
     * Check content response's content
     *
     * @param string $expected
     * @param string $actual
     *
     * @return void
     */
    protected function checkContent($expected, $actual)
    {
        if (!isset($expected)) {
            return;
        }

        if (isset($expected) && !preg_match('#' . $expected . '#i', $actual)) {
            $reason = sprintf("Expected content '%s' not found in response.", $expected);
            echo $actual;
            $this->failed($reason);
        }
    }

    /**
     * Get probe's default adapter
     *
     * @return AdapterInterface
     */
    public function getDefaultAdapter()
    {
        return new PhpCurlAdapter();
    }
}
