<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\NullAdapter;
use PhpProbe\Adapter\Response\AdapterResponseInterface;
use PhpProbe\Adapter\Response\TestAdapterResponse;
use PhpProbe\Adapter\TestAdapter;
use PhpProbe\Check\TestCheck;
use ReflectionProperty;

/**
 * Class AbstractProbeTest
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe\Probe
 */
class AbstractProbeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get a mocked AbstractProbe
     *
     * @param array $options
     * @param array $expectedOptions
     * @param bool  $construct
     *
     * @return ProbeInterface
     */
    protected function getProbeWithOptionsAndExpectedOptions(
        $options = array(),
        $expectedOptions = array(),
        $construct = true
    ) {
        $probe = $this->getMockBuilder('PhpProbe\Probe\AbstractProbe')
            ->setMethods(array('getExpectedOptions'))
            ->disableOriginalConstructor()
            ->getMock();

        $probe->expects($this->any())
            ->method('getExpectedOptions')
            ->will($this->returnValue($expectedOptions));

        /** @var ProbeInterface $probe */
        if ($construct) {
            $probe->__construct('test', $options, new NullAdapter());
        }
        return $probe;
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::__construct
     */
    public function testConstruct()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions(array(), array());
        $this->assertInstanceOf('PhpProbe\Probe\AbstractProbe', $probe);
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::configure
     */
    public function testConfigure()
    {
        $expectedOption = array(
            'testRequiredParam' => array(
                'name' => 'testRequiredParam', 'required' => true, 'type' => 'string', 'default' => 'default1')
        );

        $probe = $this->getProbeWithOptionsAndExpectedOptions(array(), $expectedOption);

        $options = array(
            'testOptionalParam' => 'value2'
        );

        $optionsBefore = $this->readAttribute($probe, 'options');
        $this->assertArrayNotHasKey('testOptionalParam', $optionsBefore);

        $probe->configure($options);

        $optionsAfter = $this->readAttribute($probe, 'options');
        $this->assertArrayHasKey('testRequiredParam', $optionsAfter);
        $this->assertArrayHasKey('testOptionalParam', $optionsAfter);

        $this->assertEquals($optionsAfter['testRequiredParam'], 'default1');
        $this->assertEquals($optionsAfter['testOptionalParam'], 'value2');
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::check
     */
    public function testCheck()
    {
        $expectedOption = array(
            'testRequiredParam' => array(
                'name' => 'testRequiredParam', 'required' => true, 'type' => 'string', 'default' => 'default1')
        );

        $probe = $this->getProbeWithOptionsAndExpectedOptions(array(), $expectedOption);


        $probe->configure(array());
        $probe->setAdapter(new TestAdapter());
        $probe->check();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::check
     */
    public function testCheckWithNoReasonExit()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $response = new TestAdapterResponse();
        $response->setStatus(AdapterResponseInterface::STATUS_UNKNOWN);

        $adapter = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapter->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        $probe->setAdapter($adapter);
        $probe->check();
        $this->assertContains(ProbeInterface::NO_REASON_FAIL_MESSAGE, $probe->getErrorMessages());
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::check
     */
    public function testCheckWithFailureAndLog()
    {
        $logger = $this->getMock('PhpProbe\Log\TestLogger');
        $logger->expects($this->once())->method('log')->withAnyParameters();

        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $response = new TestAdapterResponse();
        $response->setStatus(AdapterResponseInterface::STATUS_FAILED);

        $adapter = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapter->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        $probe->setAdapter($adapter);
        $probe->setLogger($logger);
        $probe->check();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::check
     * @covers       PhpProbe\Probe\AbstractProbe::addChecker
     */
    public function testCheckWithSuccesfulCheckerAndNoLog()
    {
        $logger = $this->getMock('PhpProbe\Log\TestLogger');
        $logger->expects($this->never())->method('log')->withAnyParameters();

        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $adapter = new TestAdapter();
        $checker = new TestCheck();
        $probe->addChecker($checker);
        $probe->setAdapter($adapter);
        $probe->setLogger($logger);
        $probe->check();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::check
     * @covers       PhpProbe\Probe\AbstractProbe::getErrorMessages
     */
    public function testCheckWithFailingCheckerAndLog()
    {
        $logger = $this->getMock('PhpProbe\Log\TestLogger');
        $logger->expects($this->once())->method('log')->withAnyParameters();

        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $adapter = new TestAdapter();
        $error = array('Test error message');
        $checker = $this->getMock('PhpProbe\Check\TestCheck', array('check'));
        $checker->expects($this->once())->method('check')->will($this->returnValue($error));

        $probe->addChecker($checker);
        $probe->setAdapter($adapter);
        $probe->setLogger($logger);
        $probe->check();
        $this->assertContains($error[0], $probe->getErrorMessages());
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::setLogger
     */
    public function testSetLogger()
    {
        $logger = $this->getMock('PhpProbe\Log\TestLogger');
        $logger->expects($this->never())->method('log')->withAnyParameters();

        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $probe->setLogger($logger);
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::checkRequiredOption
     * @covers       PhpProbe\Probe\AbstractProbe::checkConfiguration
     */
    public function testCheckRequiredOptionRequired()
    {
        $expectedOption = array(
            'option' => array('name' => 'option', 'required' => true)
        );

        $probe = $this->getProbeWithOptionsAndExpectedOptions(array(), $expectedOption);

        $this->setExpectedException('\PhpProbe\Exception\ConfigurationException');
        $probe->checkConfiguration();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::checkRequiredOption
     * @covers       PhpProbe\Probe\AbstractProbe::checkConfiguration
     */
    public function testCheckRequiredOptionNotRequired()
    {
        $expectedOption = array(
            'option' => array('name' => 'option', 'required' => false)
        );

        $probe = $this->getProbeWithOptionsAndExpectedOptions(array(), $expectedOption);

        $probe->checkConfiguration();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::checkOptionType
     * @covers       PhpProbe\Probe\AbstractProbe::checkConfiguration
     */
    public function testCheckOptionTypeWithGoodType()
    {
        $options = array('stringOption' => 'hello world');

        $expectedOption = array(
            'stringOption' => array('name' => 'stringOption', 'required' => true, 'type' => 'string')
        );

        $probe = $this->getProbeWithOptionsAndExpectedOptions($options, $expectedOption);
        $probe->checkConfiguration();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::checkOptionType
     * @covers       PhpProbe\Probe\AbstractProbe::checkConfiguration
     */
    public function testCheckOptionTypeWithBadType()
    {
        $options = array('stringOption' => 1234); // Should be a string

        $expectedOption = array(
            'stringOption' => array('name' => 'stringOption', 'required' => true, 'type' => 'string')
        );

        $probe = $this->getProbeWithOptionsAndExpectedOptions($options, $expectedOption);
        $this->setExpectedException('\PhpProbe\Exception\ConfigurationException');
        $probe->checkConfiguration();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::checkConfiguration
     */
    public function testCheckConfigurationWithBadExpectedOption()
    {
        $expectedOption = array(
            'stringOption' => array('name' => '', 'required' => true, 'type' => 'string')
        );

        $probe = $this->getProbeWithOptionsAndExpectedOptions(array(), $expectedOption);
        $this->setExpectedException('\PhpProbe\Exception\ConfigurationException');
        $probe->checkConfiguration();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::setAdapter
     * @covers       PhpProbe\Probe\AbstractProbe::getAdapter
     */
    public function testSetAdapter()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions();
        $probe->setAdapter(new TestAdapter);
        $this->assertEquals(new TestAdapter(), $probe->getAdapter());
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::__call
     */
    public function testMagicCall()
    {
        $expectedOption = array(
            'testoption' => array('name' => 'testoption', 'required' => true, 'type' => 'string')
        );
        $probe = $this->getProbeWithOptionsAndExpectedOptions(array(), $expectedOption);
        $this->assertFalse(method_exists($probe, 'testoption'));
        $probe->testoption('testValue');
        $options = $this->readAttribute($probe, 'options');
        $this->assertArrayHasKey('testoption', $options);
        $this->assertEquals('testValue', $options['testoption']);
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::checkAdapter
     */
    public function testCheckAdapterWithAdapter()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions(array(), array());
        $probe->checkAdapter();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::checkAdapter
     */
    public function testCheckAdapterWithNoAdapter()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions(array(), array(), false);
        $this->setExpectedException('\PhpProbe\Exception\ConfigurationException');
        $probe->checkAdapter();
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::hasPartiallySucceeded
     */
    public function testHasPartiallySucceeded()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $adapter = new TestAdapter();
        $error = array('Test error message');
        $checker = $this->getMock('PhpProbe\Check\TestCheck', array('check'));
        $checker->expects($this->once())->method('check')->will($this->returnValue($error));

        $probe->addChecker($checker);
        $probe->setAdapter($adapter);
        $probe->check();
        $this->assertTrue($probe->hasPartiallySucceeded());
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::hasFailed
     */
    public function testHasFailed()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $response = new TestAdapterResponse();
        $response->setStatus(AdapterResponseInterface::STATUS_FAILED);

        $adapter = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapter->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        $error = array('Test error message');
        $checker = $this->getMock('PhpProbe\Check\TestCheck', array('check'));
        $checker->expects($this->any())->method('check')->will($this->returnValue($error));

        $probe->addChecker($checker);
        $probe->setAdapter($adapter);
        $probe->check();
        $this->assertTrue($probe->hasFailed());
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::hasSucceeded
     */
    public function testHasSucceeded()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $response = new TestAdapterResponse();
        $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);

        $adapter = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapter->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        $checker = $this->getMock('PhpProbe\Check\TestCheck', array('check'));
        $checker->expects($this->any())->method('check')->will($this->returnValue(array()));

        $probe->addChecker($checker);
        $probe->setAdapter($adapter);
        $probe->check();
        $this->assertTrue($probe->hasSucceeded());
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::failed
     */
    public function testFailed()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $response = new TestAdapterResponse();
        $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);

        $adapter = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapter->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        $checker = $this->getMock('PhpProbe\Check\TestCheck', array('check'));
        $checker->expects($this->any())->method('check')->will($this->returnValue(array()));

        $probe->addChecker($checker);
        $probe->setAdapter($adapter);
        $probe->check();

        // At this point, probe should be succesful
        $this->assertTrue($probe->hasSucceeded());

        // Set probe as failed, we should get partial success (as response is succesful)
        $error = 'Error1';
        $probe->failed($error);
        $this->assertTrue($probe->hasPartiallySucceeded());
        $errors = array('Error2', 'Error3');
        $probe->failed($errors);
        $this->assertContains('Error1', $probe->getErrorMessages());
        $this->assertContains('Error2', $probe->getErrorMessages());
        $this->assertContains('Error3', $probe->getErrorMessages());
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::succeeded
     */
    public function testSucceeded()
    {
        $probe = $this->getProbeWithOptionsAndExpectedOptions();

        $response = new TestAdapterResponse();
        $response->setStatus(AdapterResponseInterface::STATUS_SUCCESSFUL);

        $adapter = $this->getMock('PhpProbe\Adapter\TestAdapter', array('getResponse'));
        $adapter->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        $checker = $this->getMock('PhpProbe\Check\TestCheck', array('check'));
        $checker->expects($this->any())->method('check')->will($this->returnValue(array('Error')));

        $probe->addChecker($checker);
        $probe->setAdapter($adapter);
        $probe->check();

        // At this point, probe should be failure
        $this->assertTrue($probe->hasPartiallySucceeded());

        // Set probe as failed, we should get partial success (as response is succesful)
        $probe->succeeded();
        $this->assertTrue($probe->hasSucceeded());
    }
}
