<?php

namespace Nagixx;

require_once 'PluginMock.php';

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category tests
 */
class NagixxTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var
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
    }

    /**
     * Tests
     */
    public function testSetGettConstruct() {
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

        /* @var $result Nagixx\Formatter */
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

        /* @var $result Nagixx\Formatter */
        $resultFormatter = $Nagixx->execute();

        $this->assertInstanceOf('Nagixx\Formatter', $resultFormatter);
        $this->assertContains('OK', $resultFormatter->getOutput());
    }
}