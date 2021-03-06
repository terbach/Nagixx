<?php

namespace Nagixx\Tests;

use Nagixx\Nagixx;
use Nagixx\Formatter;
use Nagixx\PerformanceData;

require_once 'PluginMock.php';
require_once 'PluginMockNoStatus.php';

/**
 * Testing Nagixx.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @package tests
 */
class NagixxTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var Nagixx
     */
    private $Nagixx;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () :void {
        $plugin = new PluginMock();
        $formatter = new Formatter();
        $this->Nagixx = new Nagixx($plugin, $formatter);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () :void {
        $this->Nagixx = null;
    }

    /**
     * Test the default constructor  with parameters, and correct class types of status and formatter objects.
     */
    public function testDefaultConstruct() {
        $plugin = new PluginMock();
        $formatter = new Formatter();
        $Nagixx = new Nagixx($plugin, $formatter);

        $this->assertSame($Nagixx->getPlugin(), $plugin);
        $this->assertSame($Nagixx->getFormatter(), $formatter);
    }

    /**
     * Test if formatter returns null if not injected.
     */
    public function testVersion() {
        $version = Nagixx::version();
        $regEx = '~[0-9]{1,2}.[0-9]{1,2}.[0-9]{1,3}~';

        $this->assertMatchesRegularExpression($regEx, $version);
    }

    /**
     * Tests
     */
    public function testConstructNullFormatter() {
        $plugin = new PluginMock();
        $Nagixx = new Nagixx($plugin);

        $this->assertNull($Nagixx->getFormatter());
    }

    /**
     * Test if formatter and plugin return null if not injected.
     */
    public function testConstructNullPluginAndFormatter() {
        $Nagixx = new Nagixx();

        $this->assertNull($Nagixx->getPlugin());
        $this->assertNull($Nagixx->getFormatter());
    }

    /**
     * Test setter/getter for plugin and formatter.
     */
    public function testSetGetConstruct() {
        $formatter = new Formatter();
        $plugin = new PluginMock();
        $plugin2 = new PluginMock();

        $Nagixx = new Nagixx($plugin, $formatter);

        $Nagixx->setPlugin($plugin2);
        $this->assertSame($Nagixx->getPlugin(), $plugin2);
        $this->assertSame($Nagixx->getFormatter(), $formatter);
    }

    /**
     * Test default constructor without parameters. Status has to be correct class type and correct content.
     */
    public function testExecuteDefaultConstructorNull() {
        $plugin = new PluginMock();
        $formatter = new Formatter();
        $Nagixx = new Nagixx();
        $Nagixx->setPlugin($plugin);
        $Nagixx->setFormatter($formatter);

        /* @var $result Formatter */
        $resultFormatter = $Nagixx->execute();

        $this->assertInstanceOf('Nagixx\Formatter', $resultFormatter);
        $this->assertStringContainsString('OK', $resultFormatter->getOutput());
    }

    /**
     * Test default constructor with parameters. Status has to be correct class type and correct content.
     */
    public function testExecuteDefaultConstructorNotNull() {
        $plugin = new PluginMock();
        $formatter = new Formatter();
        $Nagixx = new Nagixx($plugin, $formatter);

        /* @var $result Formatter */
        $resultFormatter = $Nagixx->execute();

        $this->assertInstanceOf('Nagixx\Formatter', $resultFormatter);
        $this->assertStringContainsString('OK', $resultFormatter->getOutput());
    }

    /**
     * Test for correct exception when executing without plugin and formatter.
     */
    public function testExecuteNoPluginAndFormatter() {
        $Nagixx = new Nagixx();

        $this->expectException('Nagixx\Exception');

        $Nagixx->execute();
    }

    /**
     * Test for correct exception when executing without formatter.
     */
    public function testExecuteNoFormatter() {
        $plugin = new PluginMock();
        $Nagixx = new Nagixx($plugin);

        $this->expectException('Nagixx\Exception');

        $Nagixx->execute();
    }

    /**
     * Test for correct exception when executing with plugin but no returning status object.
     */
    public function testExecutePluginMockNoStatus() {
        $plugin = new PluginMockNoStatus();
        $Nagixx = new Nagixx($plugin);

        $this->expectException('Nagixx\Exception');

        $Nagixx->execute();
    }

    /**
     * Test for setting performanceData to the formatter.
     */
    public function testExecutePluginMockSetPerformanceData() {
        $plugin = new PluginMock();
        $performanceData = new PerformanceData();
        $formatter = new Formatter();
        $Nagixx = new Nagixx($plugin, $formatter);

        $Nagixx->getPlugin()->setPerformanceData($performanceData);
        $Nagixx->execute();

        $this->assertSame($performanceData, $Nagixx->getPlugin()->getPerformanceData());
    }
}
