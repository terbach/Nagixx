<?php

namespace Nagixx;

class FormatterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var
     */
    private $NagixxFormatter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () {
        $this->NagixxFormatter = new Formatter();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () {
        $this->NagixxFormatter = null;
    }

    /**
     * Tests
     */
    public function testConstruct() {
        $this->assertInstanceOf('Nagixx\Formatter', $this->NagixxFormatter);
    }

    /**
     * Tests
     */
    public function testSetGet() {
        $status = new Status();

        $this->NagixxFormatter->setStatus($status);
        $this->assertSame($status, $this->NagixxFormatter->getStatus());
    }

    /**
     * Tests
     */
    public function testGetOutput() {
        $status = new Status();

        $this->NagixxFormatter->setStatus($status);
        $this->assertContains('OK', $this->NagixxFormatter->getOutput());
    }
}