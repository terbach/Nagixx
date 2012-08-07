<?php

namespace Nagixx\Tests;

use Nagixx\Nagixx;
use Nagixx\Formatter;

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
 * @category tests
 */
class NagixxTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Nagixx
     */
    private $Nagixx;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () {
        $plugin = new PluginMock();
        $formatter = new Formatter();
        $this->Nagixx = new Nagixx($plugin, $formatter);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () {
        $this->Nagixx = null;
    }

    /**
     * Tests
     */
    public function testDefaultConstruct() {
        $plugin = new PluginMock();
        $formatter = new Formatter();
        $Nagixx = new Nagixx($plugin, $formatter);

        $this->assertSame($Nagixx->getPlugin(), $plugin);
        $this->assertSame($Nagixx->getFormatter(), $formatter);
    }

    /**
     * Tests
     */
    public function testVersion() {
        $version = Nagixx::version();
        $regEx = '~[0-9]{1,2}.[0-9]{1,2}.[0-9]{1,2}~';

        $this->assertRegExp($regEx, $version);
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
     * Tests
     */
    public function testConstructNullPluginAndFormatter() {
        $Nagixx = new Nagixx();

        $this->assertNull($Nagixx->getPlugin());
        $this->assertNull($Nagixx->getFormatter());
    }

    /**
     * Tests
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
     * Tests
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
        $this->assertContains('OK', $resultFormatter->getOutput());
    }

    /**
     * Tests
     */
    public function testExecuteDefaultConstructorNotNull() {
        $plugin = new PluginMock();
        $formatter = new Formatter();
        $Nagixx = new Nagixx($plugin, $formatter);

        /* @var $result Formatter */
        $resultFormatter = $Nagixx->execute();

        $this->assertInstanceOf('Nagixx\Formatter', $resultFormatter);
        $this->assertContains('OK', $resultFormatter->getOutput());
    }

    /**
     * Tests
     */
    public function testExecuteNoPluginAndFormatter() {
        $Nagixx = new Nagixx();

        $this->setExpectedException('Nagixx\Exception');

        $Nagixx->execute();
    }

    /**
     * Tests
     */
    public function testExecuteNoFormatter() {
        $plugin = new PluginMock();
        $Nagixx = new Nagixx($plugin);

        $this->setExpectedException('Nagixx\Exception');

        $Nagixx->execute();
    }

    /**
     * Tests
     */
    public function testExecutePluginMockNoStatus() {
        $plugin = new PluginMockNoStatus();
        $Nagixx = new Nagixx($plugin);

        $this->setExpectedException('Nagixx\Exception');

        $Nagixx->execute();
    }
}