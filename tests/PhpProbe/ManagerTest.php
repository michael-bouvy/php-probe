<?php

namespace PhpProbe;

use PhpProbe\Adapter\TestAdapter;
use PhpProbe\Probe\ProbeInterface;
use PhpProbe\Probe\TestProbe;
use Symfony\Component\Yaml\Yaml;

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
    public function testCheckAllWithSuccess()
    {
        $manager = new Manager();
        $probe   = new TestProbe('testProbe');
        $probe->setAdapter(new TestAdapter());
        $manager->addProbe($probe);
        $check   = $manager->checkAll();
        $this->assertInstanceOf('PhpProbe\Manager', $check);
    }

    /**
     * @covers PhpProbe\Manager::checkAll
     */
    public function testCheckAllWithFail()
    {
        $manager = new Manager();
        $probeMock   = $this->getMock('PhpProbe\Probe\TestProbe', array('hasFailed'), array('testProbe'));
        $probeMock->expects($this->once())->method('hasFailed')->will($this->returnValue(true));

        /* @var ProbeInterface $probeMock */
        $probeMock->setAdapter(new TestAdapter());
        $manager->addProbe($probeMock);
        $manager->checkAll();
        $this->assertTrue($manager->hasFailures());
    }

    /**
     *@covers PhpProbe\Manager::end
     */
    public function testEndWithSuccess()
    {
        $manager = new Manager();
        $this->setExpectedException('PhpProbe\Exception\ExitException', '', 0);
        $manager->end();
    }

    /**
     *@covers PhpProbe\Manager::end
     */
    public function testEndWithFailure()
    {
        $manager = new Manager();
        $probeMock   = $this->getMock('PhpProbe\Probe\TestProbe', array('hasFailed'), array('testProbe'));
        $probeMock->expects($this->once())->method('hasFailed')->will($this->returnValue(true));

        /* @var ProbeInterface $probeMock */
        $probeMock->setAdapter(new TestAdapter());
        $manager->addProbe($probeMock);
        $manager->checkAll();
        $this->setExpectedException('PhpProbe\Exception\ExitException', '', 1);
        $manager->end();
    }

    /**
     *@covers PhpProbe\Manager::outputText
     */
    public function testOutputTextWithSuccess()
    {
        $manager = new Manager();
        $probeMock   = $this->getMock('PhpProbe\Probe\TestProbe', array('hasFailed'), array('testProbe'));
        $probeMock->expects($this->any())->method('hasFailed')->will($this->returnValue(false));

        /* @var ProbeInterface $probeMock */
        $probeMock->setAdapter(new TestAdapter());
        $manager->addProbe($probeMock);
        ob_start();
        $output = $manager->checkAll()->outputText(true);
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertInstanceOf('PhpProbe\Manager', $output);
        $this->assertContains('testProbe', $content);
        $this->assertContains('Success', $content);
    }

    /**
     *@covers PhpProbe\Manager::outputText
     */
    public function testOutputTextWithFailure()
    {
        $manager = new Manager();
        $probeMock   = $this->getMock('PhpProbe\Probe\TestProbe', array('hasFailed'), array('testProbe'));
        $probeMock->expects($this->any())->method('hasFailed')->will($this->returnValue(true));

        /* @var ProbeInterface $probeMock */
        $probeMock->setAdapter(new TestAdapter());
        $manager->addProbe($probeMock);
        ob_start();
        $output = $manager->checkAll()->outputText();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertInstanceOf('PhpProbe\Manager', $output);
        $this->assertContains('testProbe', $content);
        $this->assertContains('Failure', $content);
    }

    /**
     * @covers PhpProbe\Manager::outputHtml
     */
    public function testOutputHtmlWithSuccess()
    {
        $manager = new Manager();
        $probeMock   = $this->getMock('PhpProbe\Probe\TestProbe', array('hasFailed'), array('testProbe'));
        $probeMock->expects($this->any())->method('hasFailed')->will($this->returnValue(false));

        /* @var ProbeInterface $probeMock */
        $probeMock->setAdapter(new TestAdapter());
        $manager->addProbe($probeMock);
        ob_start();
        $output = $manager->checkAll()->outputHtml(true);
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertInstanceOf('PhpProbe\Manager', $output);
        $this->assertContains('testProbe', $content);
        $this->assertContains('Success', $content);
    }

    /**
     * @covers PhpProbe\Manager::outputHtml
     */
    public function testOutputHtmlWithFailure()
    {
        $manager = new Manager();
        $probeMock   = $this->getMock('PhpProbe\Probe\TestProbe', array('hasFailed'), array('testProbe'));
        $probeMock->expects($this->any())->method('hasFailed')->will($this->returnValue(true));

        /* @var ProbeInterface $probeMock */
        $probeMock->setAdapter(new TestAdapter());
        $manager->addProbe($probeMock);
        ob_start();
        $output = $manager->checkAll()->outputHtml();
        $content = ob_get_contents();
        ob_end_clean();

        $this->assertInstanceOf('PhpProbe\Manager', $output);
        $this->assertContains('testProbe', $content);
        $this->assertContains('Failure', $content);
    }

    /**
     * @covers PhpProbe\Manager::hasFailures
     */
    public function testHasFailures()
    {
        $manager = new Manager();
        $probeMock   = $this->getMock('PhpProbe\Probe\TestProbe', array('hasFailed'), array('testProbe'));
        $probeMock->expects($this->any())->method('hasFailed')->will($this->returnValue(true));

        /* @var ProbeInterface $probeMock */
        $probeMock->setAdapter(new TestAdapter());
        $manager->addProbe($probeMock);
        $manager->checkAll();

        $this->assertTrue($manager->hasFailures());
    }

    /**
     * @covers PhpProbe\Manager::importConfig
     */
    public function testImportConfig()
    {
        $manager = new Manager();
        $manager->importConfig(__DIR__ . '/../assets/config_manager.yml');
        $probes = $manager->getProbes();
        $this->assertCount(2, $probes);
        /* @var ProbeInterface $testProbe */
        $testProbe = array_shift($probes);
        $this->assertEquals('testProbe', $testProbe->getName());
    }

    /**
     * @covers PhpProbe\Manager::importConfig
     */
    public function testImportConfigWithSpecificAdapter()
    {
        $manager = new Manager();
        $manager->importConfig(__DIR__ . '/../assets/config_manager_with_adapter.yml');
        $probes = $manager->getProbes();
        $this->assertCount(1, $probes);
        /* @var ProbeInterface $testProbe */
        $testProbe = array_shift($probes);
        $this->assertInstanceOf('PhpProbe\Adapter\TestAdapter', $testProbe->getAdapter());
    }

    /**
     * @covers PhpProbe\Manager::importConfig
     */
    public function testImportConfigWithMissingFile()
    {
        $manager = new Manager();
        $this->setExpectedException('\RuntimeException');
        $manager->importConfig(__DIR__ . '/../assets/missing_config_file.yml');
    }

    /**
     * @covers PhpProbe\Manager::importProbesFromParsedFile
     */
    public function testImportProbesFromParsedFile()
    {
        $parsedFile = Yaml::parse(__DIR__ . '/../assets/config_manager_with_adapter_and_checker.yml');
        $manager = new Manager();
        $manager->importProbesFromParsedFile($parsedFile);
        $probes = $manager->getProbes();
        $this->assertEquals(1, count($probes));
        /* @var ProbeInterface $testProbe */
        $testProbe = array_shift($probes);
        $this->assertInstanceOf('PhpProbe\Adapter\TestAdapter', $testProbe->getAdapter());
    }
}
