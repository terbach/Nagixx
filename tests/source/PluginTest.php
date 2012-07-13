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
    public function testDefaultConstructAbstract() {
        require_once dirname(__FILE__) . '/../../lib/Plugin.php';

        $plugin = new \ReflectionClass('Nagixx\Plugin');
        $this->assertTrue($plugin->isAbstract());
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
    public function testGetCommandLineOptionValueNotPresent() {
        $value = $this->NagixxPluginMock->getCommandLineOptionValue('critical');

        $this->assertNull($value);
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

    public function testIsOkFalse() {
        $this->assertFalse($this->NagixxPluginMock->isOk());
    }

    public function testIsWarningFalse() {
        $this->assertFalse($this->NagixxPluginMock->isWarning());
    }

    public function testIsCriticalFalse() {
        $this->assertFalse($this->NagixxPluginMock->isCritical());
    }
}