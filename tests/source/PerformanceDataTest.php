<?php

namespace Nagixx\Tests;

use Nagixx\Exception;
use Nagixx\PerformanceData;

/**
 * Testing PerformanceData.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category tests
 */
class PerformanceDataTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var PerformanceData
     */
    private $NagixxPerformanceData;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () {
        $this->NagixxPerformanceData = new PerformanceData();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () {
        $this->NagixxPerformanceData = null;
    }

    /**
     * Test if units are used or not.
     */
    public function testUsesUnits() {
        $performanceData = new PerformanceData();
        $this->assertFalse($performanceData->usesUnits());

        $performanceData->useUnits();
        $this->assertFalse($performanceData->usesUnits());

        $performanceData->useUnits(true);
        $this->assertTrue($performanceData->usesUnits());
    }

    /**
     * Test if correct unit is used when unit using is enabled.
     */
    public function testUseUnit() {
        $performanceData = new PerformanceData();
        $performanceData->useUnits(true);

        $performanceData->setUnit(PerformanceData::UNIT_BYTE);
        $this->assertSame('B', $performanceData->getUnit());

        $performanceData->setUnit(PerformanceData::UNIT_PERCENT);
        $this->assertSame('%', $performanceData->getUnit());

        $performanceData->setUnit(PerformanceData::UNIT_TIME);
        $this->assertSame('s', $performanceData->getUnit());

        $performanceData->setUnit(PerformanceData::UNIT_COUNTER);
        $this->assertSame('c', $performanceData->getUnit());

        /* Not expected in Nagios, but I offer the possibility to do so. */
        $performanceData->setUnit('k');
        $this->assertSame('k', $performanceData->getUnit());
    }

    /**
     * Test if correct array is returned when selecting by key.
     */
    public function testGetKey() {
        $this->NagixxPerformanceData->addPerformanceData('testKey', 100, 18, 17, 10, 115);
        $this->NagixxPerformanceData->addPerformanceData('testKeySecond', 210, 28, 27, 20, 215);
        $this->NagixxPerformanceData->addPerformanceData('testKeyThird', 310, 38, 37, 30, 315);

        $this->assertArrayHasKey('warn', $this->NagixxPerformanceData->getPerformanceData('testKey'));
        $this->assertArrayHasKey('crit', $this->NagixxPerformanceData->getPerformanceData('testKey'));
        $this->assertArrayHasKey('min', $this->NagixxPerformanceData->getPerformanceData('testKey'));
        $this->assertArrayHasKey('max', $this->NagixxPerformanceData->getPerformanceData('testKey'));

        $valueObject = $this->NagixxPerformanceData->getPerformanceData('testKey');
        $this->assertSame(100, $valueObject['testKey']);
        $this->assertSame(18, $valueObject['warn']);
        $this->assertSame(17, $valueObject['crit']);
        $this->assertSame(10, $valueObject['min']);
        $this->assertSame(115, $valueObject['max']);

        $this->assertArrayHasKey('warn', $this->NagixxPerformanceData->getPerformanceData('testKeySecond'));
        $this->assertArrayHasKey('crit', $this->NagixxPerformanceData->getPerformanceData('testKeySecond'));
        $this->assertArrayHasKey('min', $this->NagixxPerformanceData->getPerformanceData('testKeyThird'));
        $this->assertArrayHasKey('max', $this->NagixxPerformanceData->getPerformanceData('testKeyThird'));

        $valueObject = $this->NagixxPerformanceData->getPerformanceData('testKeySecond');
        $this->assertSame(210, $valueObject['testKeySecond']);
        $this->assertSame(28, $valueObject['warn']);
        $this->assertSame(27, $valueObject['crit']);
        $this->assertSame(20, $valueObject['min']);
        $this->assertSame(215, $valueObject['max']);
    }

    /**
     * Test if correct exception is thrown when getting not existing performance data object.
     */
    public function testGetKeyNotFound() {
        $this->NagixxPerformanceData->addPerformanceData('testKey', 100, 18, 17, 10, 115);
        $this->NagixxPerformanceData->addPerformanceData('testKeySecond', 210, 28, 27, 20, 215);
        $this->NagixxPerformanceData->addPerformanceData('testKeyThird', 310, 38, 37, 30, 315);

        $this->assertArrayHasKey('warn', $this->NagixxPerformanceData->getPerformanceData('testKey'));
        $this->assertArrayHasKey('crit', $this->NagixxPerformanceData->getPerformanceData('testKey'));
        $this->assertArrayHasKey('min', $this->NagixxPerformanceData->getPerformanceData('testKey'));
        $this->assertArrayHasKey('max', $this->NagixxPerformanceData->getPerformanceData('testKey'));

        $this->setExpectedException('Nagixx\Exception', 'Key testKeyFourth not existing!');

        $valueObject = $this->NagixxPerformanceData->getPerformanceData('testKeyFourth');
    }
}