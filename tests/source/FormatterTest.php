<?php

namespace Nagixx;

/**
 * Testing Nagixx\Formatter.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category tests
 */
class FormatterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Formatter
     */
    private $NagixxFormatter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () {
        $this->NagixxFormatter = new Formatter();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () {
        $this->NagixxFormatter = null;
    }

    /**
     * Tests
     */
    public function testConstruct() {
        $this->assertInstanceOf('Nagixx\Formatter', $this->NagixxFormatter);
    }

    /**
     * Tests
     */
    public function testSetGetStatus() {
        $status = new Status();

        $this->NagixxFormatter->setStatus($status);
        $this->assertSame($status, $this->NagixxFormatter->getStatus());
    }

    /**
     * Tests
     */
    public function testSetGetPerformanceData() {
        $performanceData = new PerformanceData();

        $this->NagixxFormatter->setPerformanceData($performanceData);
        $this->assertSame($performanceData, $this->NagixxFormatter->getPerformanceData());
    }

    /**
     * Tests
     */
    public function testGetOutputWithoutPerformanceData1() {
        $status = new Status();

        $this->NagixxFormatter->setStatus($status);
        $this->assertContains('OK', $this->NagixxFormatter->getOutput());
        $this->assertNotContains('|', $this->NagixxFormatter->getOutput(false));
    }

    /**
     * Tests
     */
    public function testGetOutputWithoutPerformanceData2() {
        $status = new Status();

        $this->NagixxFormatter->setStatus($status);
        $this->assertContains('OK', $this->NagixxFormatter->getOutput(false));
        $this->assertNotContains('|', $this->NagixxFormatter->getOutput(false));
    }

    /**
     * Tests
     */
    public function testGetOutputWithPerformanceData() {
        $status = new Status();
        $status->setShortPluginDescription('Description');
        $status->setStatusMessage('Finished!');
        $status->setStatusNumber(Status::NAGIOS_STATUS_NUMBER_OK);
        $status->setStatusText(Status::NAGIOS_STATUS_TEXT_HOST_OK);
        $performanceData = new PerformanceData();
        $performanceData->addPerformanceData('key', 4, 2, 3, 0, 5);

        $this->NagixxFormatter->setStatus($status);
        $this->NagixxFormatter->setPerformanceData($performanceData);

        $this->assertContains('UP', $this->NagixxFormatter->getOutput(true));
        $this->assertContains('|', $this->NagixxFormatter->getOutput(true));
    }

    /**
     * Tests
     */
    public function testGetOutputWithPerformanceDataNoValues() {
        $status = new Status();
        $status->setShortPluginDescription('Description');
        $status->setStatusMessage('Finished!');
        $status->setStatusNumber(Status::NAGIOS_STATUS_NUMBER_OK);
        $status->setStatusText(Status::NAGIOS_STATUS_TEXT_HOST_OK);
        $performanceData = new PerformanceData();

        $this->NagixxFormatter->setStatus($status);
        $this->NagixxFormatter->setPerformanceData($performanceData);

        $this->assertContains('UP', $this->NagixxFormatter->getOutput(true));
        $this->assertNotContains('|', $this->NagixxFormatter->getOutput(true));
    }
}