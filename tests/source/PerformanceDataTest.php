<?php

namespace Nagixx;

use Nagixx\Exception;

/**
 * Testing Nagixx\PerformanceData.
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
     * Tests
     */
    public function testUsesUnits() {
        $this->assertFalse(PerformanceData::usesUnits());

        PerformanceData::useUnits();
        $this->assertFalse(PerformanceData::usesUnits());

        PerformanceData::useUnits(true);
        $this->assertTrue(PerformanceData::usesUnits());
    }

    /**
     * Tests
     */
    public function testUseUnit() {
        PerformanceData::useUnits(true);

        PerformanceData::setUnit(PerformanceData::UNIT_BYTE);
        $this->assertSame('B', PerformanceData::getUnit());

        PerformanceData::setUnit(PerformanceData::UNIT_PERCENT);
        $this->assertSame('%', PerformanceData::getUnit());

        PerformanceData::setUnit(PerformanceData::UNIT_TIME);
        $this->assertSame('s', PerformanceData::getUnit());

        PerformanceData::setUnit(PerformanceData::UNIT_COUNTER);
        $this->assertSame('c', PerformanceData::getUnit());

        /* Not expected in Nagios, but I offer the possibility to do so. */
        PerformanceData::setUnit('k');
        $this->assertSame('k', PerformanceData::getUnit());
    }

    /**
     * Tests
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
     * Tests
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