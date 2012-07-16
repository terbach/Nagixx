<?php

namespace Nagixx;

require_once 'PluginMock.php';
require_once 'PluginMockNoStatus.php';

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
     * @var Nagixx\PerformanceData
     */
    private $NagixxPerformanceData;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () {
        $plugin = new PluginMock();
        $formatter = new Formatter();
        $this->Nagixx = new Nagixx($plugin, $formatter);
        $this->NagixxPerformanceData = new PerformanceData();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () {
        $this->Nagixx = null;
        $this->PerformanceData = null;
    }

    /**
     * Tests
     */
    public function testDefaultConstruct() {
        $this->assertSame($this->Nagixx, $this->Nagixx);
    }
}