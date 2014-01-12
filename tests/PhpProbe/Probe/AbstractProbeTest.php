<?php

namespace PhpProbe\Probe;

use PhpProbe\Adapter\TestAdapter;
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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function abstractProbe()
    {
        $mock = $this->getMockForAbstractClass(
            '\PhpProbe\Probe\AbstractProbe',
            array('testAbstractProbe', array(), new TestAdapter()),
            '',
            false,
            true,
            true,
            array('getExpectedOptions')
        );

        $mock->expects($this->any())
            ->method('getExpectedOptions')
            ->will(
                $this->returnValue(
                    array(
                        'testRequiredParam' => array('name' => 'testRequiredParam', 'required' => true, 'type' => 'string', 'default' => 'default1')
                    )
                )
            );

        return array(array($mock));
    }

    /**
     * @covers       PhpProbe\Probe\AbstractProbe::__construct
     * @dataProvider abstractProbe
     */
    public function testConstruct($probe)
    {
        $this->assertInstanceOf('PhpProbe\Probe\AbstractProbe', $probe);
    }

    /**
     * @param AbstractProbe|\PHPUnit_Framework_MockObject_MockObject $probe
     *
     * @covers       PhpProbe\Probe\AbstractProbe::configure
     * @dataProvider abstractProbe
     */
    public function testConfigure($probe)
    {
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
     * @param AbstractProbe|\PHPUnit_Framework_MockObject_MockObject $probe
     *
     * @covers       PhpProbe\Probe\AbstractProbe::check
     * @dataProvider abstractProbe
     */
    public function testCheck($probe)
    {
        $probe->configure();
        $probe->setAdapter(new TestAdapter());
        $probe->check();
    }
}
