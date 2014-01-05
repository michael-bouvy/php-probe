<?php

namespace PhpProbe;

use PhpProbe\Adapter\TestAdapter;
use PhpProbe\Probe\TestProbe;

/**
 * Class ManagerTest
 *
 * @author  Michael BOUVY <michael.bouvy@gmail.com>
 * @package PhpProbe
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PhpProbe\Manager::__construct
     */
    public function testConstruct()
    {
        $instance = new Manager();
        $this->assertInstanceOf('PhpProbe\Manager', $instance);
    }

    /**
     * @covers PhpProbe\Manager::addProbe
     * @covers PhpProbe\Manager::getProbes
     */
    public function testAddProbe()
    {
        $manager = new Manager();
        $probe   = new TestProbe('testProbe');
        $probe->setAdapter(new TestAdapter());
        $manager->addProbe($probe);
        $probes = $manager->getProbes();
        $this->assertArrayHasKey($probe->getHash(), $probes);
    }

    /**
     * @covers PhpProbe\Manager::checkAll
     */
    public function testCheckAll()
    {
        $manager = new Manager();
        $probe   = new TestProbe('testProbe');
        $probe->setAdapter(new TestAdapter());
        $manager->addProbe($probe);
        $check   = $manager->checkAll();
        $this->assertInstanceOf('PhpProbe\Manager', $check);
    }
}
