<?php

namespace Nagixx\Tests;

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
 * @package tests
 */
class ExceptionTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var \Nagixx\Exception
     */
    private $NagixxException;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp () :void {
        $this->NagixxException = new Exception('Testing the exception!');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown () :void{
        $this->NagixxException = null;
    }

    /**
     * Test the correct class type of the exception.
     */
    public function testConstruct() {
        $this->assertInstanceOf('Nagixx\Exception', new Exception('Testing the exception!'));
    }

    /**
     * Test the correct message.
     */
    public function testMessage() {
        $this->assertStringContainsString('Testing the exception!', $this->NagixxException->getMessage());
    }
}
