<?php

namespace NagixxTests\Logging\Adapter;

use \org\bovigo\vfs\vfsStreamDirectory;
use \org\bovigo\vfs\vfsStreamWrapper;
use \org\bovigo\vfs\vfsStream;

use Nagixx\Logging\LoggerContainer;
use Nagixx\Logging\Adapter\File;

/**
 * Description...
 *
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.0
 * @since 0.5.0.3
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @package Oxx
 * @package Tests
 */
class FileTest extends \PHPUnit\Framework\TestCase {

    /**
     * @var string
     */
    protected $logFile = '';

    /**
     * @group all
     */
    public function setUp() :void {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('rootDir'));
        $this->logFile = vfsStream::url('rootDir/myLogFile.log');
    }

    /**
     * @group all
     */
    public function tearDown() :void {
    }

    /**
     * @group all
     */
    public function testConstruct() {
        $fileLogger = new File($this->logFile);

        $this->assertInstanceOf('Nagixx\Logging\Adapter\File', $fileLogger);
    }

    /**
     * @group all
     */
    public function testLog() {
        $fileLogger = new File($this->logFile);

        $fileLogger->log('MyMessage', LoggerContainer::LOGLEVEL_INFO);

        $logContent = file_get_contents($this->logFile);

        $this->assertStringContainsString('MyMessage', $logContent);
        $this->assertStringContainsString(':: 2 ::', $logContent); // Loglevel::Info
    }
}
