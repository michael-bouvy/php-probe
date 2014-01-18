<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\Response\AdapterResponseInterface;
use PhpProbe\Adapter\Response\TestAdapterResponse;
use PhpProbe\Adapter\TestAdapter;

/**
 * Class TcpProbeTest
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class TcpProbeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PhpProbe\Probe\TcpProbe::__construct
     */
    public function testConstruct()
    {
        $testAdapter = new TestAdapter();
        $instance    = new TcpProbe('testTcpProbe', array(), $testAdapter);
        $this->assertInstanceOf('PhpProbe\Probe\ProbeInterface', $instance);
    }

    /**
     * @covers PhpProbe\Probe\TcpProbe::__construct
     */
    public function testConstructWithDefaultAdapter()
    {
        $instance = new TcpProbe('testTcpProbe', array());
        $this->assertInstanceOf('PhpProbe\Probe\ProbeInterface', $instance);
        $this->assertInstanceOf('PhpProbe\Adapter\AdapterInterface', $instance->getAdapter());
    }

    /**
     * @covers PhpProbe\Probe\TcpProbe::check
     */
    public function testCheckWithSuccess()
    {
        $adapterResponse = new TestAdapterResponse();
        $adapterResponse->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);

        $adapterMock = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapterMock->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($adapterResponse));

        $probe = new TcpProbe(
            'testTcpProbe',
            array(
                'host' => '1.2.3.4',
                'port' => 1234
            )
        );
        /* @var AdapterInterface $adapterMock */
        $probe->setAdapter($adapterMock);
        $probe->check();
        $this->assertTrue($probe->hasSucceeded());
    }

    /**
     * @covers PhpProbe\Probe\TcpProbe::check
     */
    public function testCheckWithError()
    {
        $adapterResponse = new TestAdapterResponse();
        $adapterResponse->setStatus(AdapterResponseInterface::STATUS_FAILED);
        $adapterResponse->setError('Test Adapter Error message');

        $adapterMock  = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapterMock->expects($this->any())
            ->method('getResponse')
            ->will($this->returnValue($adapterResponse));

        $probe = new TcpProbe(
            'testTcpProbe',
            array(
                'host' => '1.2.3.4',
                'port' => 1234
            )
        );

        /* @var AdapterInterface $adapterMock */
        $probe->setAdapter($adapterMock);
        $probe->check();
        $this->assertFalse($probe->hasSucceeded());
    }

    /**
     * @covers PhpProbe\Probe\TcpProbe::getDefaultAdapter
     */
    public function testGetDefaultAdapter()
    {
        $probe = new TcpProbe('testTcpProbe');
        $adapter = $probe->getDefaultAdapter();
        $this->assertInstanceOf('\PhpProbe\Adapter\FsockopenAdapter', $adapter);
    }
}
