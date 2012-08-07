<?php

namespace Nagixx;

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
        $this->assertContains('OK', $resultFormatter->getOutput());
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
        $this->assertContains('OK', $resultFormatter->getOutput());
    }

    /**
     * Test for correct exception when executing without plugin and formatter.
     */
    public function testExecuteNoPluginAndFormatter() {
        $Nagixx = new Nagixx();

        $this->setExpectedException('Nagixx\Exception');

        $Nagixx->execute();
    }

    /**
     * Test for correct exception when executing without formatter.
     */
    public function testExecuteNoFormatter() {
        $plugin = new PluginMock();
        $Nagixx = new Nagixx($plugin);

        $this->setExpectedException('Nagixx\Exception');

        $Nagixx->execute();
    }

    /**
     * Test for correct exception when executing with plugin but no returning status object.
     */
    public function testExecutePluginMockNoStatus() {
        $plugin = new PluginMockNoStatus();
        $Nagixx = new Nagixx($plugin);

        $this->setExpectedException('Nagixx\Exception');

        $Nagixx->execute();
    }
}