<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\TestAdapter;

/**
 * Class HttpProbeTest
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class HttpProbeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PhpProbe\Probe\HttpProbe::__construct
     */
    public function testConstruct()
    {
        $testAdapter = new TestAdapter();
        $instance    = new HttpProbe('testHttpProbe', array(), $testAdapter);
        $this->assertInstanceOf('PhpProbe\Probe\ProbeInterface', $instance);
    }

    /**
     * @covers PhpProbe\Probe\HttpProbe::__construct
     */
    public function testConstructWithDefaultAdapter()
    {
        $instance = new HttpProbe('testHttpProbe', array());
        $this->assertInstanceOf('PhpProbe\Probe\ProbeInterface', $instance);
        $this->assertInstanceOf('PhpProbe\Adapter\AdapterInterface', $instance->getAdapter());
    }

    /**
     * @covers PhpProbe\Probe\HttpProbe::check
     */
    public function testCheckWithSuccess()
    {
        $adapterMock = $this->getMock('PhpProbe\Adapter\TestAdapter', array('check'));
        $adapterMock->expects($this->once())
            ->method('check')
            ->will($this->returnValue(true));
        $probe = new HttpProbe(
            'testHttpProbe',
            array(
                'url' => 'http://www.test.com'
            )
        );
        /* @var AdapterInterface $adapterMock */
        $probe->setAdapter($adapterMock);
        $probe->check();
        $this->assertTrue($probe->hasSucceeded());
    }

    /**
     * @covers PhpProbe\Probe\HttpProbe::check
     */
    public function testCheckWithError()
    {
        $errorMessage = 'Error';
        $adapterMock  = $this->getMock('PhpProbe\Adapter\TestAdapter', array('check'));
        $adapterMock->expects($this->once())
            ->method('check')
            ->will($this->returnValue($errorMessage));
        $probe = new HttpProbe(
            'testHttpProbe',
            array(
                'url' => 'http://www.test.com'
            )
        );

        /* @var AdapterInterface $adapterMock */
        $probe->setAdapter($adapterMock);
        $probe->check();
        $this->assertEquals($errorMessage, $probe->getErrorMessage());
    }

    /**
     * @covers PhpProbe\Probe\HttpProbe::getDefaultAdapter
     */
    public function testGetDefaultAdapter()
    {
        $probe = new HttpProbe('testHttpProbe');
        $adapter = $probe->getDefaultAdapter();
        $this->assertInstanceOf('\PhpProbe\Adapter\PhpCurlAdapter', $adapter);
    }
}
