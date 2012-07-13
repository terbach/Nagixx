<?php

namespace Nagixx;

use Nagixx\Plugin;

require_once 'PluginMock.php';

/**
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category tests
 */
class PluginTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var
     */
    private $NagixxPluginMock;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () {
        $this->NagixxPluginMock = new PluginMock();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () {
        $this->NagixxPluginMock = null;
    }

    /**
     * Tests
     */
    public function testAbstract() {
        require_once dirname(__FILE__) . '/../../lib/Plugin.php';

        $plugin = new \ReflectionClass('Nagixx\Plugin');
        $this->assertTrue($plugin->isAbstract());

        $initPlugin = $plugin->getMethod('initPlugin');
        $execute = $plugin->getMethod('execute');
        $this->assertTrue($initPlugin->isAbstract());
        $this->assertTrue($execute->isAbstract());
    }

    /**
     * Tests
     */
    public function testAbstractIsConcrete() {
        $plugin = new \ReflectionClass('Nagixx\PluginMock');
        $this->assertFalse($plugin->isAbstract());

        $initPlugin = $plugin->getMethod('initPlugin');
        $execute = $plugin->getMethod('execute');
        $this->assertFalse($initPlugin->isAbstract());
        $this->assertFalse($execute->isAbstract());
    }

    /**
     * Tests
     */
    public function testGetPluginDescription() {
        $this->assertSame('PluginMock', $this->NagixxPluginMock->getPluginDescription());
    }

    /**
     * Tests
     */
    public function testGetPluginVersion() {
        $this->assertSame('1.0', $this->NagixxPluginMock->getPluginVersion());
    }

    /**
     * Tests
     */
    public function testHasCommandLineOptionFalse() {
        $hasOption = null;

        $hasOption = $this->NagixxPluginMock->hasCommandLineOption('critical');

        $this->assertFalse($hasOption);
    }

    /**
     * Tests
     */
    public function testHasCommandLineOptionTrue() {
        $this->NagixxPluginMock->setCritical(10);
        $hasOption = null;

        $hasOption = $this->NagixxPluginMock->hasCommandLineOption('critical');
        $this->assertTrue($hasOption);
    }

    /**
     * Tests
     */
    public function testGetCommandLineOptionValueNotPresent() {
        $value = $this->NagixxPluginMock->getCommandLineOptionValue('critical');

        $this->assertNull($value);
    }

    /**
     * Tests
     */
    public function testGetCommandLineOptionValuePresent() {
        $this->NagixxPluginMock->setCritical(10);
        $value = $this->NagixxPluginMock->getCommandLineOptionValue('critical');

        $this->assertSame(10, $value);
    }

    /**
     * Tests
     */
    public function testHasCommandLineArgumentFalse() {
        $hasOption = null;

        $hasOption = $this->NagixxPluginMock->hasCommandLineArgument('critical');

        $this->assertFalse($hasOption);
    }

    /**
     * Tests
     */
    public function testGetCommandLineArgumentValueNotPresent() {
        $value = $this->NagixxPluginMock->getCommandLineArgumentValue('critical');

        $this->assertNull($value);
    }

    /**
     * Tests
     */
    public function testParseThreshold() {
        $this->assertTrue(is_array($this->NagixxPluginMock->parseThreshold(5)));
    }

    /**
     * Tests
     */
    public function testCalcStatus() {
        $this->assertNull($this->NagixxPluginMock->calcStatus(5));
    }

    /**
     * Tests
     */
    public function testIsOkFalse() {
        $this->assertFalse($this->NagixxPluginMock->isOk());
    }

    /**
     * Tests
     */
    public function testIsWarningFalse() {
        $this->assertFalse($this->NagixxPluginMock->isWarning());
    }

    /**
     * Tests
     */
    public function testIsCriticalFalse() {
        $this->assertFalse($this->NagixxPluginMock->isCritical());
    }

    /**
     * Tests
     */
    public function testTimer() {
        $time = $this->NagixxPluginMock->startTimer();
        $this->assertSame($time, $this->NagixxPluginMock->getTimer());
    }

    /**
     * Tests
     */
    public function testGetTimerDiff() {
        $time = $this->NagixxPluginMock->startTimer();
        $this->assertSame($time, $this->NagixxPluginMock->getTimer());
        $this->assertNotSame($time, $this->NagixxPluginMock->getTimerDiff());
    }
}