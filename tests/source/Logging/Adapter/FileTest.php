<?php

namespace OxxTests\Logging\Adapter;

use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

use Oxx\TestCase as OxxTestCase;
use Oxx\Logging\LoggerContainer;
use Oxx\Logging\Adapter\LoggingAdapter;
use Oxx\Logging\Adapter\File;

/**
 * Description...
 *
 * @author terbach <terbach@netbixx.com>
 * @version 1.0.0.0
 * @since 0.5.0.3
 * @copyright 2012 netbixx GmbH (http://www.netbixx.com)
 *
 * @category Oxx
 * @package Tests
 */
class FileTest extends OxxTestCase {

    /**
     * @var string
     */
    protected $logFile = '';

    /**
     * @group all
     */
    public function setUp() {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('rootDir'));
        $this->logFile = vfsStream::url('rootDir/myLogFile.log');
    }

    /**
     * @group all
     */
    public function tearDown() {
    }

    /**
     * @group all
     */
    public function testConstruct() {
        $fileLogger = new File($this->logFile);

        $this->assertInstanceOf('Oxx\Logging\Adapter\File', $fileLogger);
    }

    /**
     * @group all
     */
    public function testLog() {
        $fileLogger = new File($this->logFile);

        $fileLogger->log('MyMessage', LoggerContainer::LOGLEVEL_INFO);

        $logContent = file_get_contents($this->logFile);

        $this->assertContains('MyMessage', $logContent);
        $this->assertContains(':: 2 ::', $logContent); // Loglevel::Info
    }
}