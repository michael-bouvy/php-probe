<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\AdapterInterface;
use PhpProbe\Adapter\Reponse\AdapterResponseInterface;
use PhpProbe\Adapter\Reponse\TestAdapterResponse;
use PhpProbe\Adapter\TestAdapter;

/**
 * Class DatabaseProbeTest
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class DatabaseProbeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PhpProbe\Probe\DatabaseProbe::__construct
     */
    public function testConstruct()
    {
        $testAdapter = new TestAdapter();
        $instance    = new DatabaseProbe('testDatabaseProbe', array(), $testAdapter);
        $this->assertInstanceOf('PhpProbe\Probe\ProbeInterface', $instance);
    }

    /**
     * @covers PhpProbe\Probe\DatabaseProbe::__construct
     */
    public function testConstructWithDefaultAdapter()
    {
        $instance = new DatabaseProbe('testDatabaseProbe', array());
        $this->assertInstanceOf('PhpProbe\Probe\ProbeInterface', $instance);
        $this->assertInstanceOf('PhpProbe\Adapter\AdapterInterface', $instance->getAdapter());
    }

    /**
     * @covers PhpProbe\Probe\DatabaseProbe::check
     */
    public function testCheckWithSuccess()
    {
        $adapterResponse = new TestAdapterResponse();
        $adapterResponse->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);

        $adapterMock = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapterMock->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($adapterResponse));

        $probe = new DatabaseProbe(
            'testDatabaseProbe',
            array(
                'user' => 'test'
            )
        );
        /* @var AdapterInterface $adapterMock */
        $probe->setAdapter($adapterMock);
        $probe->check();
        $this->assertTrue($probe->hasSucceeded());
    }

    /**
     * @covers PhpProbe\Probe\DatabaseProbe::check
     */
    public function testCheckWithError()
    {
        $adapterResponse = new TestAdapterResponse();
        $adapterResponse->setStatus(AdapterResponseInterface::STATUS_FAILED);
        $adapterResponse->setError('Test Adapter Error message');

        $adapterMock = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapterMock->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($adapterResponse));

        $probe = new DatabaseProbe(
            'testDatabaseProbe',
            array(
                'user' => 'test'
            )
        );

        /* @var AdapterInterface $adapterMock */
        $probe->setAdapter($adapterMock);
        $probe->check();
        $this->assertFalse($probe->hasSucceeded());
    }

    /**
     * @covers PhpProbe\Probe\DatabaseProbe::getDefaultAdapter
     */
    public function testGetDefaultAdapter()
    {
        $probe = new DatabaseProbe('testDatabaseProbe');
        $adapter = $probe->getDefaultAdapter();
        $this->assertInstanceOf('\PhpProbe\Adapter\PhpMysqlAdapter', $adapter);
    }
}
