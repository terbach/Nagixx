<?php

namespace Nagixx;

use Nagixx\Exception;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var
     */
    private $NagixxException;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp ()
    {
        $this->NagixxException = new Exception();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown ()
    {
        $this->NagixxException = null;
    }

    /**
     * Tests
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('Exception', new Exception());
    }
}