<?php

namespace OxxTests\Logging;

use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamFile;

use Oxx\TestCase as OxxTestCase;
use Oxx\Logging\LoggerContainer;
use Oxx\Exception\ExceptionGeneral;

use OxxTests\Logging\Adapter\FileTestClass;

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
class LoggerContainerTest extends OxxTestCase {

    /**
     * @var string
     */
    protected $logFileName = '';

    /**
     * @var vfsStreamDirectory
     */
    protected $logFile = null;

    /**
     * @group all
     */
    public function setUp() {
        parent::setup();
        $this->logFileName = 'testLogging.log';

        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('rootDir'));
        $this->logFile = vfsStream::url('rootDir/' . $this->logFileName);

        $this->fileLogger = new FileTestClass($this->logFile);
    }

    /**
     * @group all
     */
    public function tearDown() {
        parent::tearDown();
    }

    /**
     * @group all
     */
    public function testConstruct() {
        $container = new LoggerContainer();

        $this->assertEmpty($container->getAdapters());
    }

    /**
     * @group all
     */
    public function testAddAdapterGetAdapters() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $container->addAdapter(new FileTestClass($this->logFile));
        $adapters = $container->getAdapters();

        $this->assertSameSize(array(1), $adapters);
        foreach ($adapters as $adapter) {
            $this->assertInstanceOf('Oxx\Logging\Adapter\LoggingAdapter', $adapter);
        }
    }

    /**
     * @group all
     */
    public function testSetAdaptersGetAdapters() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $container->setAdapters(array(new FileTestClass($this->logFile), new FileTestClass($this->logFile)));
        $adapters = $container->getAdapters();

        $this->assertSameSize(array(1, 2), $adapters);
        foreach ($adapters as $adapter) {
            $this->assertInstanceOf('Oxx\Logging\Adapter\LoggingAdapter', $adapter);
        }
    }

    /**
     * @group all
     */
    public function testSetAdaptersGetAdaptersWrong() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $this->setExpectedException('Oxx\Exception\ExceptionGeneral', 'Wrong class type for adapter!');

        $container->setAdapters(array(new FileTestClass($this->logFile), new \stdClass()));
    }

    /**
     * @group all
     */
    public function testClearAdapters() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $container->setAdapters(array(new FileTestClass($this->logFile), new FileTestClass($this->logFile)));
        $adapters = $container->getAdapters();

        $this->assertSameSize(array(1, 2), $adapters);
        foreach ($adapters as $adapter) {
            $this->assertInstanceOf('Oxx\Logging\Adapter\LoggingAdapter', $adapter);
        }

        $container->clearAdapters();
        $this->assertEmpty($container->getAdapters());
    }

    /**
     * @group all
     */
    public function testLogException() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $this->setExpectedException('\Oxx\Exception\ExceptionGeneral');
        $container->log('', $container::LOGLEVEL_CRITICAL);
    }

    /**
     * @group all
     */
    public function testlog() {
        $container = new LoggerContainer();
        $this->assertEmpty($container->getAdapters());

        $container->setAdapters(array(new FileTestClass($this->logFile), new FileTestClass($this->logFile)));

        $container->log('MyMessage', $container::LOGLEVEL_DEBUG);

        $logContent = file_get_contents($this->logFile);

        $this->assertContains('MyMessage', $logContent);
        $this->assertContains(':: 1 ::', $logContent); // Loglevel::Debug
    }
}