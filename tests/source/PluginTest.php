<?php

namespace Nagixx;

use Nagixx\Plugin;

require_once 'PluginMock.php';

/**
 *
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
}