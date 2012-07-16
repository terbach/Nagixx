<?php

namespace Nagixx;

use Nagixx\Exception;

/**
 * Testing Nagixx\Exception.
 *
 * @author terbach <terbach@netbixx.com>
 * @license See licence file LICENCE.md
 * @version 1.0.0
 * @since 1.0.0
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category tests
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Nagixx\Exception
     */
    private $NagixxException;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () {
        $this->NagixxException = new Exception('Testing the exception!');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () {
        $this->NagixxException = null;
    }

    /**
     * Tests
     */
    public function testConstruct() {
        $this->assertInstanceOf('Nagixx\Exception', new Exception('Testing the exception!'));
    }

    /**
     * Tests
     */
    public function testMessage() {
        $this->assertContains('Testing the exception!', $this->NagixxException->getMessage());
    }
}