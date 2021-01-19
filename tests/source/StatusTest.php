<?php

namespace Nagixx\Tests;

use Nagixx\Status;

/**
 * Testing Status.
 *
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @package tests
 */
class StatusTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var Status
     */
    private $NagixxStatus;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () :void {
        $this->NagixxStatus = new Status();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () :void {
        $this->NagixxStatus = null;
    }

    /**
     * Test the default constructur and the status settings.
     */
    public function testDefaultConstruct() {
        $this->assertEquals(0, $this->NagixxStatus->getStatusNumber());
        $this->assertEquals('', $this->NagixxStatus->getShortPluginDescription());
        $this->assertEquals(' OK - ', $this->NagixxStatus->getStatusText());
        $this->assertEquals('', $this->NagixxStatus->getStatusMessage());
    }

    /**
     * Test an individual constructor and the status settings.
     */
    public function testIndividualConstruct() {
        $this->NagixxStatus = null;
        $this->NagixxStatus = new Status(Status::NAGIOS_STATUS_NUMBER_WARNING, '', '', 'My Message while warning!');

        $this->assertEquals(1, $this->NagixxStatus->getStatusNumber());
        $this->assertEquals('', $this->NagixxStatus->getShortPluginDescription());
        $this->assertEquals('  - ', $this->NagixxStatus->getStatusText());
        $this->assertEquals('My Message while warning!', $this->NagixxStatus->getStatusMessage());
    }
}
