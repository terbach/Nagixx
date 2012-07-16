<?php

namespace Nagixx;

/**
 * Testing Nagixx\Formatter.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category tests
 */
class FormatterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Nagixx\Formatter
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